# Análise Arquitetural — Projeto Homee (até momento atual)

**Data:** 2026-10-28  
**Status:** Em desenvolvimento / Correções aplicadas (seeder, factory, repository, controller, request)

---

## 1. Visão Geral do Projeto

Projeto de API imobiliária (`homee.laravel`) com domínio de **propriedades** (`Property`) e **usuários/admins** (`User`/`Admin`). A aplicação expõe rotas REST sob `/api/v1` para cadastro (`POST /property/create`), leitura individual (`GET /property/{slug}`) e listagem com filtros (`GET /property`).

---

## 2. Arquitetura Utilizada

### 2.1 Padrões principais
- **CQRS** (Command Query Responsibility Segregation): separa comandos (`CreatePropertyCommand`) de consultas (`GetPropertyBySlugQuery`, `ListPropertiesQuery`).
- **Layers / Clean Architecture simplificada:**
  - `Domain/`: enums (`TransactionTypeEnum`), contratos/repositórios (`PropertyReadRepositoryInterface`).
  - `Application/`: handlers de comandos/consultas (`CreatePropertyHandler`, `GetPropertyBySlugHandler`), actions (`GetUserByIdAction`).
  - `Infrastructure/`: implementações Eloquent (`PropertyReadRepository`, `PropertyWriteRepository`).
  - `Http/`: controllers (`ShowPropertyController`, `ListPropertiesController`), requests (`ListPropertiesRequest`), resources (`PropertyResource`).

### 2.2 Fluxo típico de leitura (`GET /property/{slug}`)

```
Route (api.php) -> Controller (ShowPropertyController) -> QueryBus -> Query (GetPropertyBySlugQuery)
  -> Handler (GetPropertyBySlugHandler) -> Repository (PropertyReadRepository::findBySlug)
    -> Model (Property::select(...)->where('slug', ...)->firstOrFail())
      -> Resource (PropertyResource) -> JSON
```

### 2.3 Fluxo típico de escrita (`POST /property/create`)

```
Route -> Controller (RegisterPropertyController) -> Request (StorePropertyRequest)
  -> CommandBus -> Command (CreatePropertyCommand) -> Handler (CreatePropertyHandler)
    -> Repository (PropertyWriteRepository) -> Model (Property::create) -> Response
```

---

## 3. Modelo de Dados — `Property`

Arquivo: `app/Models/Property.php`

| Campo | Tipo DB | Cast / Fillable | Observação |
|-------|---------|----------------|------------|
| `id` | PK | — | Auto-increment |
| `title` | `string(40)` | fillable | Título do imóvel |
| `slug` | `string` (unique) | fillable | Gerado via `Str::slug()` + random |
| `valueInCents` | `integer` | fillable | Valor em centavos |
| `description` | `string(255)` | fillable | Descrição textual |
| `amenities` | `json` | `casts => 'array'` | Array de strings (ex: `['ac', 'piscina']`) |
| `location` | `string(255)` | fillable | Cidade/Estado/Bairro |
| `transactionType` | `enum('buy','rental')` | fillable | Definido por `TransactionTypeEnum` |
| `created_at` / `updated_at` | timestamps | — | Automático |

### 3.1 Factory e Seeder (estado atual)
- `database/factories/PropertyFactory.php`: usa `fake()` (Faker) para gerar dados realistas.
- `database/seeders/PropertySeeder.php`: `Property::factory()->count(20)->create()`. 
- `database/seeders/DatabaseSeeder.php`: corrigido `User::firstOrCreate()` para evitar duplicação de `test@example.com`.

---

## 4. Problemas Corrigidos Até o Momento (registro `.docs/` / `.explain/`)

| Problema | Arquivo | Correção aplicada |
|----------|---------|-------------------|
| `Property::factory()` indefinido | `app/Models/Property.php` | Adicionado `HasFactory` |
| `PropertySeeder` com array fixo sem faker | `database/seeders/PropertySeeder.php` | Substituído por factory |
| `PropertyFactory` erro `catchPhrase` | `database/factories/PropertyFactory.php` | `catchPhrase()` → `sentence()`; `Str::random(8)` |
| `PropertyReadRepository::find()` sem args | `app/Infrastructure/Persistence/Eloquent/PropertyReadRepository.php` | `->find()` → `->where('slug', $slug)->firstOrFail()` |
| `PropertyResource` retornava `slug` preenchido, resto `null` | `app/Http/Resources/Api/V1/PropertyResource.php` | Estava usando `$request` ao invés de `$this`; **não corrigido ainda** (detectado) |
| `transactionType` falhava ao enviar `null` | `app/Http/Requests/Api/V1/Property/ListPropertiesRequest.php` | Substituído `Rule::in` por validação customizada (`TransactionTypeEnum`) |

---

## 5. Fluxo de Dados — Busca (`GET /api/v1/property`)

### 5.1 Request
Arquivo: `app/Http/Requests/Api/V1/Property/ListPropertiesRequest.php`

Campos aceitos (com validação):
- `search` (string, max 100) — busca textual
- `title` (string, max 40)
- `valueInCents` (integer, ≥0)
- `transactionType` (nullable, custom: `buy`/`rental`/`null`)
- `amenities` (array) + `amenities.*` (string)
- `per_page`, `page`, `sort_by`, `sort_dir`

### 5.2 Controller
Arquivo: `app/Http/Controllers/Api/V1/Property/ListPropertiesController.php`

Aplicação de filtros:
- `title`: `ilike` (case-insensitive PostgreSQL)
- `valueInCents`: `where` exato
- `transactionType`: `where` direto (se `null`, ignorado via `filled()` — atual comportamento)
- `amenities`: `whereJsonContains` para cada item do array
- Sort: `orderBy` dinâmico
- Paginação: `paginate($perPage)`

### 5.3 Resposta
Retorna `PropertyResource` paginado (mas `PropertyResource` ainda usa `$request` ao invés de `$this`, gerando dados nulos — pendente de correção).

---

## 6. Fluxo de Dados — Busca por Slug (`GET /api/v1/property/{slug}`)

- Rota: `routes/api.php` linha 22 (`Route::get("/{slug}", ShowPropertyController::class)`)
- Controller: `ShowPropertyController` → `QueryBus` → `GetPropertyBySlugQuery`
- Handler: `GetPropertyBySlugHandler` → `PropertyReadRepository::findBySlug($slug)`
- Repositório: `Property::query()->select(...)->where('slug', $slug)->firstOrFail()`
- Recurso: `PropertyResource` (pendente: `$request` vs `$this`)

---

## 7. Estrutura de Diretórios Relevantes

```
app/
  Domain/
    Properties/Enums/TransactionTypeEnum.php
    Users/Repositories/PropertyReadRepositoryInterface.php
    Users/Repositories/PropertyWriteRepositoryInterface.php
  Application/
    Users/Queries/GetPropertyBySlug/ (Query + Handler)
    Users/Queries/ListProperties/ (Query + Handler)
    Users/Commands/CreateProperty/ (Command + Handler)
  Infrastructure/
    Persistence/Eloquent/
      PropertyReadRepository.php
      PropertyWriteRepository.php
  Http/
    Controllers/Api/V1/Property/
      ListPropertiesController.php (filtros implementados)
      ShowPropertyController.php
      RegisterPropertyController.php
    Requests/Api/V1/Property/
      ListPropertiesRequest.php
      StorePropertyRequest.php
    Resources/Api/V1/
      PropertyResource.php (problema detectado: $request)
  Models/
    Property.php (HasFactory adicionado)
  Rules/
    TransactionTypeRule.php

database/
  factories/PropertyFactory.php
  seeders/PropertySeeder.php / DatabaseSeeder.php
  migrations/ (create_properties_table)
```

---

## 8. Fluxo Completo: HTTP Request até Save no Banco

### 8.1 Fluxo de Escrita (POST → Salvar)

| Etapa | Arquivo | Responsabilidade | O que faz |
|-------|---------|------------------|-----------|
| **1. Rota** | `routes/api.php` | Definição de endpoint | Mapeia a URL `POST /api/v1/property/create` ao `RegisterPropertyController` |
| **2. Controller** | `app/Http/Controllers/Api/V1/Property/RegisterPropertyController.php` | Orquestração da ação | Recebe a requisição, valida dados (via Request) e delega para CommandBus |
| **3. Request (Validação)** | `app/Http/Requests/Api/V1/Property/StorePropertyRequest.php` | Validação de entrada | Valida os dados recebidos (name, slug, valueInCents, etc.) usando regras de formulário |
| **4. Command** | `app/Application/Users/Commands/CreateProperty/CreatePropertyCommand.php` | DTO de comando | Encapsula os dados validados em um objeto imutável para o Handler |
| **5. CommandBus** | Laravel's Bus (ou dispatcher customizado) | Despacho do comando | Encaminha o Command ao Handler correto |
| **6. Handler** | `app/Application/Users/Commands/CreateProperty/CreatePropertyHandler.php` | Lógica de negócio | Processa as regras de domínio, transforma dados, chama o repositório para persistir |
| **7. Repository (Write)** | `app/Infrastructure/Persistence/Eloquent/PropertyWriteRepository.php` | Persistência | Implementa `create(array $data)` usando `Property::create($data)` |
| **8. Model** | `app/Models/Property.php` | Entidade | Representa a tabela `properties`, herda de `Model`, define `fillable` e `casts` |
| **9. Database** | `database/migrations/xxxx_create_properties_table.php` | Schema | Define a estrutura da tabela no PostgreSQL |

### 8.2 Fluxo de Leitura (GET → Buscar)

| Etapa | Arquivo | Responsabilidade | O que faz |
|-------|---------|------------------|-----------|
| **1. Rota** | `routes/api.php` | Definição de endpoint | Mapeia a URL `GET /api/v1/property/{slug}` ao `ShowPropertyController` |
| **2. Controller** | `app/Http/Controllers/Api/V1/Property/ShowPropertyController.php` | Orquestração da ação | Recebe o parâmetro `{slug}`, delega para QueryBus |
| **3. Query** | `app/Application/Users/Queries/GetPropertyBySlug/GetPropertyBySlugQuery.php` | DTO de consulta | Encapsula os parâmetros de busca (`slug`) |
| **4. QueryBus** | Laravel's Bus (ou dispatcher customizado) | Despacho da query | Encaminha a Query ao Handler correto |
| **5. Handler** | `app/Application/Users/Queries/GetPropertyBySlug/GetPropertyBySlugHandler.php` | Lógica de negócio | Busca os dados via repositório read |
| **6. Repository (Read)** | `app/Infrastructure/Persistence/Eloquent/PropertyReadRepository.php` | Consulta | Implementa `findBySlug($slug)` usando `Property::query()->where('slug', $slug)->firstOrFail()` |
| **7. Model** | `app/Models/Property.php` | Entidade | Representa a tabela `properties`, aplica filtros e relacionamentos |
| **8. Resource** | `app/Http/Resources/Api/V1/PropertyResource.php` | Formatação da resposta | Transforma o Model em JSON estruturado para API |
| **9. Response** | Controller | HTTP Response | Retorna JSON com os dados formatados |

### 8.3 Símbolos usados no fluxo

- **solid line →**: chamada direta (método)
- **dashed line ⤏**: despacho (Bus)
- **.../**: camada/lógica

---

## 9. Fluxo de Dados — Leitura de Customer (GET `/api/v1/customer/{id}`)

### 9.1 Arquitetura do fluxo de leitura (baseado no padrão CQRS)

Utilizando os mesmos padrões de `Property` (CQRS simplificado com QueryBus), o fluxo de leitura para `Customer` foi implementado para buscar pelo `id` do usuário, conforme solicitado.

**Arquivos criados/alterados:**
- `app/Http/Controllers/Api/V1/Customer/ShowCustomerController.php`
- `app/Application/Users/Queries/GetCustomerById/GetCustomerByIdQuery.php`
- `app/Application/Users/Queries/GetCustomerById/GetCustomerByIdHandler.php`
- `app/Domain/Users/Repositories/CustomerReadRepositoryInterface.php`
- `app/Infrastructure/Persistence/Eloquent/Read/CustomerReadRepository.php`
- `routes/api.php`

### 9.2 Fluxo de Leitura (`GET /api/v1/customer/{id}`)

```
Route (api.php) -> Controller (ShowCustomerController)
  -> QueryBus -> Query (GetCustomerByIdQuery)
    -> Handler (GetCustomerByIdHandler) -> Repository (CustomerReadRepository::findById)
      -> Model (Customer::query()->select(...)->find($id))
        -> Resource (CustomerResource) -> JSON
```

### 9.3 Tabela — Etapas do fluxo de leitura para Customer

| Etapa | Arquivo | O que faz |
|-------|---------|-----------|
| **1. Rota** | `routes/api.php` | `Route::get('/{id}', ShowCustomerController::class)->whereNumber('id')` |
| **2. Controller** | `ShowCustomerController.php` | Recebe `{id}`, delega para `QueryBus` com `GetCustomerByIdQuery` |
| **3. Query (DTO)** | `GetCustomerByIdQuery.php` | Encapsula `public int $userId` |
| **4. QueryBus** | `QueryBusInterface` | Despacha a `Query` para o `Handler` |
| **5. Handler** | `GetCustomerByIdHandler.php` | Usa `CustomerReadRepositoryInterface::findById($query->userId)` |
| **6. Repository (Read)** | `CustomerReadRepository.php` | Implementa `Customer::query()->select(...)->find($id)` |
| **7. Modelo** | `app/Models/Customer.php` | Entidade com `fillable`: `name`, `email`, `phone`, `address`, `password`, `role`; `casts`: `email_verified_at`, `password` (hashed), `role` (enum) |
| **8. Resource** | `CustomerResource.php` | Formata a resposta JSON: `id`, `name`, `email`, `phone`, `address` |
| **9. Response** | Controller (`__invoke`) | Retorna `new CustomerResource($customer)` |

### 9.4 Observação sobre a busca por `userId`

A busca é feita utilizando o `id` do usuário (`$id` do usuário associado à tabela `customers`), conforme solicitado. O `GetCustomerByIdQuery` aceita `public int $userId`, que é passado diretamente ao repositório de leitura (`findById`).

---

---

## 10. Observações sobre Redis (documentado em `.docs/redis-property-search.md`)

Foi documentado (não implementado no código) como adicionar Redis ao fluxo de busca:
- Cache por slug: `Cache::remember('property:slug:' . $slug, ...)`
- Cache por lista: chave baseada em hash dos parâmetros do request
- Invalidação ao criar/atualizar
- Observação sobre `amenities` (JSON) e `$casts` no model

---

## 9. Estado Atual dos Problemas Abertos

| Problema | Severidade | Arquivo | Status |
|----------|------------|---------|--------|
| `PropertyResource` usa `$request` | Alta | `PropertyResource.php` | **Detectado, não corrigido** |
| `search` no controller executa `ilike` apenas em `title`, não em `description`/`location` | Média | `ListPropertiesController.php` | **Parcial** (mostrado como exemplo) |
| `transactionType = null` no controller | Baixa | `ListPropertiesController.php` | **Funciona** (`filled()` ignora) |
| `PropertyFactory` usa `sentence()` limitando a 40 chars | Baixa | `PropertyFactory.php` | Corrigido |

---

## 10. Stack / Tecnologias
- **Framework:** Laravel (versão do projeto via `vendor/laravel`)
- **Banco:** PostgreSQL (`pgsql`, porta 5432, banco `homee_db`)
- **Cache:** Redis documentado, não ativado no `.env` verificado
- **Testes/Seed:** Faker, Factory, Seeder
- **Validação:** `FormRequest` + `Rule::in` customizado para enum
- **Arquitetura:** CQRS + DDD simplificado + Eloquent como infraestrutura

---

*Documento gerado sem alteração de arquivos `.php`.*

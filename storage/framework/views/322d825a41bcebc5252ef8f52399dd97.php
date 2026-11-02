<?php $__env->startSection('content'); ?>
    <div id="app"></div>

    <script src="<?php echo e(\Scalar\Facades\Scalar::cdn()); ?>"></script>

    <script>
       Scalar.createApiReference('#app', <?php echo \Scalar\Facades\Scalar::configurationJson(); ?>)
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('scalar::layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/vendor/scalar/laravel/resources/views/reference.blade.php ENDPATH**/ ?>
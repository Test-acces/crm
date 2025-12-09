<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildSchema()); ?>

</div>
<?php /**PATH D:\Projet\web\crm_leger\vendor\filament\schemas\resources\views/components/grid.blade.php ENDPATH**/ ?>
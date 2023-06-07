<?php
$columns = get_sub_field('columns');
$colSize = count($columns) === 4 ? '3' : (count($columns) === 3 ? '4' : '6');
?>

<div class="column_content">
    <div class="container">
        <div class="row">
            <?php foreach($columns as $column) : ?>
                <div class="col-md-<?php echo $colSize ?>">
                    <?php echo $column['content'] ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
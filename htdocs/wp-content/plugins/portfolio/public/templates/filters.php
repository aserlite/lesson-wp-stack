<?php
$area_object = get_field_object('area');
$choices = $area_object['choices'];
global $wp;
$action = home_url($wp->request);
?>
<form method="post" action="<?php echo $action ?>">
    <select name="filter-area">
        <?php foreach ($choices as $value => $choice) : ?>
            <option
                value="<?php echo $value ?>"
                <?php echo $_POST && $_POST['filter-area'] === $value ? 'selected' : '' ?>
            >
                <?php echo $choice ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="OK">
</form>
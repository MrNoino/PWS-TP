<a href="?option=Statistics" class="list-group-item list-group-item-action <?php echo (isset($_GET["option"]) && $_GET["option"] === "Statistics" || !isset($_GET["option"]) ? "active" : "") ?>" aria-current="true">
    <?php echo $statistics_label ?>
</a>
<a href="?option=Personal Data" class="list-group-item list-group-item-action <?php echo (isset($_GET["option"]) && $_GET["option"] === "Personal Data" ? "active" : "") ?>" aria-current="true">
    <?php echo $personal_data_label ?>
</a>
<a href="?option=Settings" class="list-group-item list-group-item-action <?php echo (isset($_GET["option"]) && $_GET["option"] === "Settings" ? "active" : "") ?>">
    <?php echo $settings_label ?>
</a>
<?php
$key = key($_POST);
if ($key == "vendeur") {
    $page = "/impression/etiquette/vendeur";
} elseif ($key == "objet") {
    $page = "/impression/etiquette/objet";

}
unset($_POST[$key]);
?>

<form id="form" action="<?php echo $page; ?>" method="post">
    <?php
    foreach ($_POST as $a => $b) {
        echo '<input type="hidden" name="' . htmlentities($a) . '" value="' . htmlentities($b) . '">';
    }
    ?>
</form>
<script type="text/javascript">
    document.getElementById('form').submit();
</script>
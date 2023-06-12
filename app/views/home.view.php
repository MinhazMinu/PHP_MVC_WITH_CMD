<h1>
    Home view page
</h1>

<?php
$page = new \Core\Pager;
$page->ul_styles = "list-style: none !important; display: flex;";
$page->li_styles = "margin:5px 10px; border:solid thin #ccc; padding:5px 10px; border-radius:5px; ";
$page->a_styles  = "text-decoration: none; color: #333; font-weight: bold;";

$page->display()
?>
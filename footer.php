<?php wp_footer(); ?>
<code>
<?php

if ( is_super_admin() ) {
    echo 'Template: ' .  get_active_template();
}
?>
</code>
</main>
</div>
<footer>
    With Support From
    <div class="logos">
        <a href="https://www.act.gov.au/environment/parks-and-conservation"> <img src="<?= get_template_directory_uri() . "/assets/images/act-govt.svg" ?>"></a>
        <a href="https://woodlandsandwetlands.org.au/"> <img src="<?= get_template_directory_uri() . "/assets/images/WWT_Logo.png" ?>"></a>
        <a href="http://dfat.gov.au/people-to-people/foundations-councils-institutes/australia-japan-foundation/pages/australia-japan-foundation.aspx"> <img src="<?= get_template_directory_uri() . "/assets/images/aus-japan.svg" ?>"></a>
    </div>
</footer>
</body>
</html>

<form name="addform" id="mainform" method="get" action="">
    <fieldset id="main">
    <?php if ($success) : ?>
        <p>
            You just inserted the following code
        </p>
    <?php else : ?>
        <p>
            Error: <?=$message?>
        </p>
    <?php endif; ?>
    </fieldset>
</form>

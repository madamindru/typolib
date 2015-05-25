<form name="addform" id="mainform" method="get" action="">
<div id="result"></div>
    <fieldset id="main">
        <?php if (isset($locale_selector)) : ?>
        <fieldset>
            <label>Locale</label>
            <select name="locale" title="Locale" id="locale_selector">
            <?=$locale_selector?>
            </select>
        </fieldset>
        <br/>
        <?php endif; ?>

        <fieldset>
            <p>Enter the name of the code:<br />
            <input type="text" name="name" /></p>
        </fieldset>
        <br/>
        <fieldset>
            <input type="checkbox" name="common" id="common"/><label for="common">Use the common code</label>
        </fieldset>
        <br/>
        <input type="submit" value="Create" alt="Create" />
    </fieldset>
</form>

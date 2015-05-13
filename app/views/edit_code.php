<form name="addform" id="mainform" method="get" action="">
<div id="result"></div>
    <fieldset id="main">
        <fieldset>
            <p>Enter the new name of the code:<br />
            <input type="text" name="name" value="<?=isset($name) ? $name : '' ?>"/></p>
        </fieldset>
        <br/>
        <fieldset>
            <input type="checkbox" name="common" id="common" <?=isset($common) && $common ? 'checked' : '' ?>/><label for="common">Use the common code</label>
        </fieldset>
        <br/>
        <input type="hidden" name="old_code" value="<?=isset($old_code) ? $old_code : '' ?>" />
        <input type="hidden" name="locale" value="<?=isset($locale) ? $locale : '' ?>" />
        <input type="submit" name="edit_code_sent" value="Edit" alt="Edit" />
    </fieldset>
</form>

<form name="addform" id="mainform" method="get" action="">
<div id="result"></div>
    <fieldset id="main">
        <fieldset>
            <label>Locale</label>
            <select name="locale" title="Locale" id="locale_selector">
            <?=$locale_selector?>
            </select>
        </fieldset>

        <br/>
        <fieldset>
            <p>Enter the name of the code:<br />
            <input type="text" name="name"/></p>
        </fieldset>
        <br/>
        <fieldset>
            <p>Use the common code:<br />
            <input type="checkbox" name="common"/></p>
        </fieldset>
        <br/>
        <input type="submit" value="Add" alt="Add" />
    </fieldset>
</form>

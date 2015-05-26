<form name="addform" id="mainform" method="get" action="">

     <fieldset id="main">
        <?php if (isset($locale_selector)) : ?>
        <fieldset>
            <label>Locale</label>
            <select name="locale" title="Locale" id="locale_selector">
            <?=$locale_selector?>
            </select>
        </fieldset>
        <?php endif; ?>

        <?php if (isset($code_selector)) : ?>
        <fieldset>
            <label>Code</label>
            <select name="code" title="Code" id="code_selector">
            <?=$code_selector?>
            </select>
        </fieldset>

        <fieldset>
        <input type="submit" name="check" value="Check" alt="Check" />
        </fieldset> <br />
        <fieldset id="main">
            <label for "enterText"> Enter your text:</label> </fieldset>
        <fieldset id="main">
            <label for "identifiedMistakes"> Identified Mistakes:</label>
        </fieldset>
        <br />

            <TEXTAREA name="enterText" ROWS=3 COLS=15 >
            </TEXTAREA>

            <TEXTAREA name="IdentifiedMistakes: " ROWS=3 COLS=15>
            </TEXTAREA>

      <?php endif; ?>
</form>

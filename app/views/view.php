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
        <?php endif; ?>

        <?php if (isset($ruletypes_selector)) : ?>
        <fieldset>
            <label>Rule type</label>
            <select name="type" title="Rule type" id="addrule_type">
            <?=$ruletypes_selector?>
            </select>
        </fieldset>
        <?php endif; ?>

        <a href="/insert" class="button button-green" role="button">Add</a>
        <input type="submit" name="edit_code" value="Edit" alt="Edit" />
        <input type="submit" name="delete_code" value="Delete" alt="Delete" />

        <br/>
        <?php include VIEWS . 'if_then_template.php'; ?>

        <br/>
        <fieldset>
            <p>Enter a comment:<br />
            <input type="text" name="comment" id="comment"/></p>
        </fieldset>

        <br/>
        <input type="submit" id="submitRule" value="Add" alt="Add" />
    </fieldset>
    <div id="results"><?php include VIEWS . 'view_treeview.php'; ?></div>
</form>

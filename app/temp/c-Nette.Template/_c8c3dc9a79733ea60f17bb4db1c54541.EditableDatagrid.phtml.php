<?php //netteCache[01]000242a:2:{s:4:"time";s:21:"0.85842100 1263846229";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:5:"Cache";i:1;s:9:"checkFile";}i:1;s:87:"D:\www\00_Vyvoj\EditableDataGrid\app\components\EditableDatagrid/EditableDatagrid.phtml";i:2;i:1263844606;}}}?><?php
// file D:\www\00_Vyvoj\EditableDataGrid\app\components\EditableDatagrid/EditableDatagrid.phtml
//

$_cb = LatteMacros::initRuntime($template, NULL, 'cd851e69a8'); unset($_extends);

if (SnippetHelper::$outputAllowed) {

/**
 * @license LGPL
 */


    $grid = $component;
    $editovatelnePolicka = $component->editableFields;

    $sloupecky = array();
    $i=0;
    foreach($grid->getComponents(FALSE, "IDataGridColumn") AS $dgColName => $dgColumn) {
        $sloupecek = $sloupecky[$i] = (object)null;
        $sloupecek->editable = false;
        if(isset($editovatelnePolicka[$dgColName])) {
            $column = $editovatelnePolicka[$dgColName];
            $sloupecek->editable = true;
            $sloupecek->type = $column->type;
            $sloupecek->name = $column->columnName;

            // Dictionary is used for example in selects. (in datagrid is translated value, but in database we have only ID)
            $dict = $sloupecek->dictionary = (object)null;
            $dict->keys = array();
            $dict->values = array();
            foreach($column->dictionary AS $key => $val){
                $dict->keys[] = $key;
                $dict->values[] = $val;
            }
            $sloupecek->formControl = $column->__toString();
        }
        $i++;
    }

    $sloupecky_json = json_encode($sloupecky)
?>
<style type="text/css">
    .ui-effects-transfer { border: 2px dotted gray; } 
</style>
<script type="text/JavaScript">

    /*$("table.datagrid tbody").livequery(function(){
        $(this).selectable({ filter:"td"});
    });*/

    // :not(.checker,.actions)

    $("table.datagrid").livequery(function(){
        $(this).wrap("<div style=\"position: relative;left: 0px; top: 0px;\"></div>");
    })

    $("table.datagrid tbody tr.editable td:not(.checker,.actions)").livequery(function(){
        var sloupecky = <?php echo $sloupecky_json ?>;
        var t = $(this);
        var cisloSloupecku = t.parent().find("td:not(.checker)").index(t);
        var infoSloupecku = sloupecky[cisloSloupecku];
        var idRadku = t.parent().attr("id").split("___");
        idRadku = idRadku[idRadku.length-1];

        if(infoSloupecku.editable === true){
            if(infoSloupecku.type == "TEXT" || infoSloupecku.type == "TEXTAREA"){
                t.wrapInner("<div></div>").find("div").editable();
            }else{
                var value = t.text();
                t.html("");
                var input = $(infoSloupecku.formControl);
                if(infoSloupecku.dictionary.values.length>0){
                    // Použijem překladový slovník
                    var keyValue = array_search(value,infoSloupecku.dictionary.values);
                    var keyValue = infoSloupecku.dictionary.keys[keyValue];
                    input.attr("value",keyValue);
                }else{
                    input.attr("value",value);
                }
                input.appendTo(t);
            }
            
            var input = $("(input,select,div[contenteditable]):first",t);

            /**
             * Getts column value
             */
            var getColumnValue = function(column){
                if(column.is("div")){
                    return column.text();
                }else{
                    return column.attr("value");
                }
            }

            /**
             * Setts column value
             */
            var setColumnValue = function(column,value){
                if(column.is("div")){
                    column.text(value);
                }else{
                    column.attr("value",value);
                }
            }

            // Když se klikne na buňku aktivujeme políčko v ní
            t.click(function(){
                input.focus();
            })
            input.focus(function(){
                var t = $(this);
                t.data("origValue",getColumnValue($(this)));
            })
            .change(function(){
                var t = $(this);
                var td = t.parent();
                var link = <?php echo TemplateHelpers::escapeJs($control->link("saveColumnData!", array('nazevPolicka' => "data-nazevPolicka-data",'cisloSloupecku' => "data-cisloSloupecku-data",'data' => "data-data-data",'cisloRadku' => "data-cisloRadku-data",'dataGrid' => $grid->getUniqueId(),'origSha1' => "data-sha1-data"))) ?>;
                link = str_replace("data-nazevPolicka-data", rawurlencode(infoSloupecku.name), link);
                link = str_replace("data-cisloRadku-data", rawurlencode(idRadku), link);
                link = str_replace("data-cisloSloupecku-data", rawurlencode(cisloSloupecku), link);
                link = str_replace("data-data-data", rawurlencode(getColumnValue(t)), link);
                link = str_replace("data-sha1-data", rawurlencode(sha1(t.data("origValue"))), link);
                var origValue = t.data("origValue");

                var position = td.position();
                var loading = $('<span class="ui-icon ui-icon-signal-diag" style="position: absolute;"></span>')
                    .css("left",position.left+td.get(0).clientWidth-16)
                    .css("top",position.top+td.get(0).clientHeight-16)
                    .appendTo(td);

                $.ajax({
                    type: "GET",
                    url: link,
                    dataType: "json",
                    complete:function(){
                        loading.hide();
                    },
                    /*error: function(){
                        // Ošetřeno globálně
                    },*/
                    success: function(payload){
                        var srvData = payload.editableDatagrid;
                        $(".editableDatagrid-validationError",td).remove();

                        if(srvData.success != true){
                            if(typeof srvData.errors != "undefined" && srvData.errors.length>0){
                                var position = td.position();
                                var errorContainer = $('<div style="position:absolute;" class="ui-corner-bottom ui-corner-tr ui-state-error editableDatagrid-validationError"></div>')
                                    .css("left",position.left+ (td.get(0).clientWidth-1))
                                    .css("top", position.top + (td.get(0).clientHeight))
                                    .hide()
                                    .appendTo(td);
                                $('<span class="ui-icon ui-icon-arrowthick-1-nw" style="position: absolute;left: -12px;top: -12px;"></span>').appendTo(errorContainer);
                                errorContainer._remove = function(){
                                    $(this).fadeOut(500,function(){
                                        $(this).remove();
                                    })
                                }
                                var errorContainer2 = $("<ul></ul>").appendTo(errorContainer);
                                for(var i in srvData.errors){
                                    $("<li></li>").html(srvData.errors[i]).appendTo(errorContainer2)
                                }
                                var puvodniHodnota = $('<div style="padding: 8px;">Původní hodnota buňky:</div>').appendTo(errorContainer);
                                $('<div class="ui-corner-all ui-state-default" style="padding: 5px;margin-top: 5px;cursor:pointer;">'+origValue+'</div>')
                                    .click(function(){
                                        setColumnValue(t,origValue);
                                        errorContainer._remove();
                                        t.select();
                                    })
                                    .appendTo(puvodniHodnota);
                                errorContainer.fadeIn(500,function(){
                                    td.effect("transfer",{ to: errorContainer, className: 'ui-effects-transfer'},500);
                                });
                            }
                            t.focus().select();
                        }else{
                            // Data úspěšně uložena
                            var position = td.position();
                            var ok = $('<span class="ui-icon ui-icon-check" style="position: absolute;"></span>')
                                .css("left",position.left+td.get(0).clientWidth-16)
                                .css("top",position.top+td.get(0).clientHeight-16)
                                .appendTo(td);
                                ok.fadeOut(2000,function(){
                                    $(this).remove();
                                });
                        }
                        $.nette.success(payload);
                    }
                });
            })
        }
    });
</script>

<?php } if ($_cb->foo = SnippetHelper::create($control, "editableDatagridJavaScript")) { $_cb->snippets[] = $_cb->foo ;if (($control->paginator->length > $control->maxDataOnPageInFastMode)): ?>
    <script type="text/JavaScript">
        $("table.datagrid tbody tr").each(function(){
            $(this).click(function(e){
                $(this).addClass("editable").unbind("click");
                $("input, select, div[contenteditable]",this).livequery(function(){
                    $(e.target).click();
                })
            })
        })
    </script>
<?php else: ?>
    <script type="text/JavaScript">
        $("table.datagrid tbody tr").each(function(){
            $(this).addClass("editable");
        })
    </script>
<?php endif ;array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { 
}

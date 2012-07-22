<style>
  td { border:1px solid black; border-right:none;border-top:none; }
  table { border-right: 1px solid black;border-top:1px solid black; }
  input { border:none; }
</style>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">

var editor = {
    type_map: 'map',
    type_text: 'text',
    type_list: 'list',
    
    find_last_subelement: function(elementId)
    {
        var current = elementId;
        // row loop
        while ( $('#row_' + current + '_0')[0] != undefined )
        {
            // current_0 is defined, append it
            current += '_0';
            // column loop
            while ( $('#row_' + current)[0] != undefined )
            {
                var pieces = current.split('_');
                pieces[pieces.length - 1]++;
                current = pieces.join('_');
            }
            // we've gone one too far, take off the last element
            var pieces = current.split('_');
            pieces[pieces.length - 1]--;
            current = pieces.join('_');
        }
        return current;
    },
    
    kill_subelements: function(elementId)
    {
        var current = elementId;
        // alert('killing elements under ' + elementId);
        // row loop
        while ( $('#row_' + current + '_0')[0] != undefined )
        {
            // current_0 is defined, append it
            current += '_0';
            // column loop
            while ( $('#row_' + current)[0] != undefined )
            {
                $('#row_' + current).remove();
                this.kill_subelements(current);
                var pieces = current.split('_');
                pieces[pieces.length - 1]++;
                current = pieces.join('_');
            }
        };
    },
    
    get_type: function(elementId)
    {
        var sel = $('#type_' + elementId)[0].value;
        if ( sel == 'map' )
            return this.type_map;
        else if ( sel == 'text' )
            return this.type_text;
        else
            return this.type_list;
    },

    change_type: function(elementId)
    {
        var defText = '<input type="text" value="value" name="value_' + elementId + '" />';
        var defMap = '';
        var defList = '';
        var type = this.get_type(elementId);
        var valueCell = $('#value_cell_' + elementId)[0];
        if ( type == this.type_map )
        {
            valueCell.innerHTML = defMap;
            this.add_map(elementId);
        }
        else if ( type == this.type_text )
        {
            valueCell.innerHTML = defText;
            this.destroy_map(elementId);
        }
        else
            valueCell.innerHTML = defList;
    },
    
    add_map: function(elementId)
    {
        // adds a map at the selected elementId
        this.add_row(elementId, elementId + '_0');
    },
    
    destroy_map: function(elementId)
    {
        // destroy a map under the elementId selected
        this.kill_subelements(elementId);
    },
    
    get_indent: function(elementId)
    {
        var pieces = elementId.split('_');
        var indents = pieces.length - 1;
        var indent = '';
        for ( the_i = 0; the_i < indents; the_i++ )
        {
            indent += '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return indent;
    },
    
    plus_click: function(elementId)
    {
        var pieces = elementId.split('_');
        // increment id for next item
        pieces[pieces.length - 1]++;
        var newId = pieces.join('_');
        this.add_row(elementId, newId);
    },
    
    add_row: function(afterElementId, newElementId)
    {
        afterElementId = this.find_last_subelement(afterElementId);
        var defStr = ''
+          '<tr id="row_' + newElementId + '">'
+            '<td>  '
+            this.get_indent(newElementId)
+            '  <input type="button" id="plus_' + newElementId + '" value="+" onclick="editor.plus_click(\'' + newElementId + '\');" />'
+            '</td>'
+            '<td>'
+              '<input type="text" value="key" name="name_' + newElementId + '" />'
+            '</td>'
+            '<td>'
+              '<select name="type_' + newElementId + '" id="type_' + newElementId + '" onchange="editor.change_type(\'' + newElementId + '\')">'
+                '<option value="text">Text</option>'
+                '<option value="map">Map</option>'
//+                '<option value="list">List</option>'
+              '</select>'
+            '</td>'
+            '<td id="value_cell_' + newElementId + '">'
+              '<input type="text" value="value" name="value_' + newElementId + '" />'
+            '</td>'
+          '</tr>';
        $('#row_' + afterElementId).after(defStr);
    }
};


</script>

<form action="save_data_store.php" method="POST" />

<h2>Data Store Editor</h2>
<p>File loaded: {$file}</p>
<p>
  Filename: <input type="text" value="{$file}" name="filename" />
  File type: 
  <select name="filetype">
    <option value="json">json</option>
    <option value="serial">serial</option>
  </select>
  <input type="submit" id="save_0" value="Save" />
  <input type="button" id="cancel_0" value="Cancel" />
</p>
<table cellpadding="0" cellspacing="0" id="base_table">
  {$form}
</table>

</form>
  
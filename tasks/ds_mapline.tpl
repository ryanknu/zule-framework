
  <tr id="row_{$id}">
    <td>
    {$indent}
    <input type="button" id="plus_{$id}" value="+" onclick="editor.plus_click('{$id}');" />
    </td>
    <td>
      <input type="text" value="{$key}" name="name_{$id}" />
    </td>
    <td>
      <select name="type_{$id}" id="type_{$id}" onchange="editor.change_type('{$id}')">
        <option value="text">Text</option>
        <option value="map" selected>Map</option>
      </select>
    </td>
    <td id="value_cell_{$id}">&nbsp;</td>
  </tr>
  
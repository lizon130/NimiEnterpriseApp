<tr class="product{{$number}}" data-row="{{$number}}">
    <td>
        <select name="product[]" class="form-control product_select product_select{{$number}}" data-row="{{$number}}" required>
            <option value="">Product Select </option>
            @foreach ($products as $product)
                <option value="{{ $product->id}}">{{ $product->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control price price{{$number}}" name="price[]" placeholder="Price" >
    </td>
    <td>
        <input type="number" class="form-control qty qty{{$number}}" min="0" value="0" name="qty[]" placeholder="Quantity">
    </td>
    <td>
        <input type="text" class="form-control subtotal subtotal{{$number}}" name="subtotal[]" placeholder="Subtotal" readonly>
    </td>
    <td>
        <input type="text" class="form-control note note{{$number}}" name="notes[]" placeholder="Note">
    </td>
    <td><a href="" type="button" data-row="{{$number}}" class="btn btn-sm btn-danger remove_product" id="product{{$number}}"><i class="fa fa-trash"></i></a></td>
</tr>

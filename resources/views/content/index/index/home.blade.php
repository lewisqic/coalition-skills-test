@extends('layouts.index')

@section('content')

<h1 class="mb-5">Products</h1>

<h3 class="mb-4">Add New Product</h3>

<form action="{{ url('') }}" method="post" class="" id="add_product_form">
    {{ csrf_field() }}

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Product Name</label>
        <div class="col-sm-9">
            <input type="text" name="name" class="form-control" placeholder="Name" value="" required autofocus>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Quantity in Stock</label>
        <div class="col-sm-9">
            <input type="number" name="quantity" class="form-control" placeholder="0" value="" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Price Per Item</label>
        <div class="col-sm-9">
            <input type="number" step="any" name="price" class="form-control" placeholder="0.00" value="" required>
        </div>
    </div>

    <div class="form-group row mt-4">
        <div class="col-sm-9 ml-auto">
            <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Add Product</button>
        </div>
    </div>

</form>

<hr class="my-5">

<h3 class="mb-4">List Products</h3>


<table class="table table-striped" id="list_products" data-products='{{ $products_json }}'>
    <thead>
        <tr>
            <th>Product name</th>
            <th>Quantity in stock</th>
            <th>Price per item</th>
            <th>Datetime submitted</th>
            <th>Total value number</th>
        </tr>
    </thead>
    <tbody>
        {{-- rows inserted via ajax --}}
        <tr class="no-results">
            <td class="text-center" colspan="5"><em class="text-muted">No Results Found</em></td>
        </tr>
    </tbody>
    <tfoot>
        <tr class="results-found" style="display: none;">
            <td colspan="5" class="text-right">TOTAL: $<span class="grand-total"></span></td>
        </tr>
    </tfoot>
</table>

@endsection

@push('scripts')

    <script type="text/javascript">

        $(document).ready(function() {

            $('#add_product_form').on('submit', function(e) {
                e.preventDefault();
                // ajax submit our product form
                $('#add_product_form').ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {

                    },
                    success: function(data) {
                        // reset form
                        $('#add_product_form input').val('');
                        // set our updated products data
                        var products = JSON.stringify(data.all_products);
                        $('#list_products').attr('data-products', products);
                        loadProducts();
                    }
                });
                return false;
            });

            // load our products list
            var loadProducts = function() {

                var data = JSON.parse($('#list_products').attr('data-products'));
                
                var grandTotal = data.grand_total;
                var products = data.products;

                if ( products.length ) {

                    $('.no-results').hide();
                    $('.results-found').show();
                    $('.grand-total').html(grandTotal);
                    $('#list_products tbody').empty();

                    // load our product rows
                    var rows = '';
                    $(products).each(function(index, product) {
                        rows += '<tr>' +
                                    '<td>' + product.name + '</td>' +
                                    '<td>' + product.quantity + '</td>' +
                                    '<td>$' + product.price + '</td>' +
                                    '<td>' + product.created_at_formatted + '</td>' +
                                    '<td>$' + product.total + '</td>' +
                              '</tr>';
                    });
                    $('#list_products tbody').append(rows);

                }


            };

            loadProducts();

        });

    </script>

@endpush
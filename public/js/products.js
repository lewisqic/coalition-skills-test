$(document).ready(function() {

    token = $('input[name="_token"]').val();

    // create a new product
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

    // delete a product
    $('#list_products').on('click', '.delete', function(e) {
        e.preventDefault();
        var id = $(this).closest('tr').attr('data-id');

        $.ajax({
            url: 'delete',
            data: {
                _token: token,
                id: id
            },
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function() {
            },
            success: function(data) {
                // set our updated products data
                var products = JSON.stringify(data.all_products);
                $('#list_products').attr('data-products', products);
                loadProducts();
            }
        });

    });

    // edit a product
    $('#list_products').on('click', '.edit', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $tr = $(this).closest('tr');
        var id = $tr.attr('data-id');

        if ( $this.find('.fa').hasClass('fa-edit') ) {

            $tr.find('td.editable').each(function(index, el) {
                var input = '<input type="text" name="' + $(el).attr('data-name') + '" value="' + $(el).text().replace(/\$/, '') + '">';
                $(el).html(input);
            });
            $this.find('.fa').removeClass('fa-edit text-primary').addClass('fa-check text-success');

        } else {

            $.ajax({
                url: 'update',
                data: {
                    name: $tr.find('input[name="name"]').val(),
                    quantity: $tr.find('input[name="quantity"]').val(),
                    price: $tr.find('input[name="price"]').val(),
                    _token: token,
                    id: id
                },
                type: 'POST',
                dataType: 'json',
                beforeSubmit: function() {
                },
                success: function(data) {

                    // reset the edit button
                    $this.find('.fa').removeClass('fa-check text-success').addClass('fa-edit text-primary');

                    // set our updated products data
                    var products = JSON.stringify(data.all_products);
                    $('#list_products').attr('data-products', products);
                    loadProducts();

                }
            });

        }


    });

    // load our products list
    var loadProducts = function() {

        var data = JSON.parse($('#list_products').attr('data-products'));

        var grandTotal = data.grand_total;
        var products = data.products;

        $('#list_products tbody').empty();
        $('.grand-total').html(grandTotal);

        if ( products.length > 0 ) {

            $('.no-results').hide();
            $('.results-found').show();

            // load our product rows
            var rows = '';
            $(products).each(function(index, product) {
                rows += '<tr data-id="' + product.id + '">' +
                    '<td class="editable" data-name="name">' + product.name + '</td>' +
                    '<td class="editable" data-name="quantity">' + product.quantity + '</td>' +
                    '<td class="editable" data-name="price">$' + product.price + '</td>' +
                    '<td>' + product.created_at_formatted + '</td>' +
                    '<td>$' + product.total + '</td>' +
                    '<td><a href="#" class="edit mr-2"><i class="fa fa-edit text-primary"></i></a> <a href="#" class="delete text-danger"><i class="fa fa-trash-o"></i></a></td>' +
                    '</tr>';
            });
            $('#list_products tbody').append(rows);

        } else {

            $('.no-results').show();
            $('.results-found').hide();

        }


    };

    loadProducts();

});
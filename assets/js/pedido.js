var $ = require('jquery');

var $collectionHolder;

// setup an "add a tag" link
//var $newLinkLi = $('<li></li>').append($addItemLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of items
    var $addItemLink = $('#modal-pedido-add-produto');
    $collectionHolder = $('ul.items');
    var itemDoFormSF = $("#pedido_venda_items").parent().prev().remove();

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('li').length);

    $addItemLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new item form (see next code block)
        addItemForm($collectionHolder, $addItemLink);
    });
});

function addItemForm($collectionHolder, $addItemLink) {
    // Get the data-prototype explained earlier
    var prototype = $addItemLink.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a item" link li
    var $newFormModal = $('#modalItem-body').html(newForm);
    $('#pedido_venda_items_' + index + '_precoUnitario').prop('readonly', true);
    $('#pedido_venda_items_' + index + '_total').prop('readonly', true);

    $('#pedido_venda_items_' + index + '_produto').change(function(e){
        var selected = this.value;
        var valor = '';
        if(selected) {
            valor = $('#' + selected.toString()).val();
            $("#pedido_venda_items_" + index + "_produto_id").val(selected.toString());
        }
        $('#pedido_venda_items_' + index + '_precoUnitario').attr('data-valor', valor);
        $('#pedido_venda_items_' + index + '_precoUnitario').attr('value', valor.replace('.', ','));
        calculaPrecoFinal();
    });

    $('#pedido_venda_items_' + index + '_quantidade').on('keyup', function(e){
        calculaPrecoFinal();
    });

    $('#pedido_venda_items_' + index + '_percentualDesconto').on('keyup', function(e){
        calculaPrecoFinal();
    });

    function calculaPrecoFinal() {
        var precoUnitario = $('#pedido_venda_items_' + index + '_precoUnitario').attr('data-valor');
        var quantidade = $('#pedido_venda_items_' + index + '_quantidade').val();
        var desconto = $('#pedido_venda_items_' + index + '_percentualDesconto').val();

        var preco = quantidade * precoUnitario;
        var precoFinal = preco - (preco * desconto / 100);
        $('#pedido_venda_items_' + index + '_total').attr('value', precoFinal.toFixed(2).toString().replace('.', ','));
    }


    $('#modalItem-adicionar-btn').click(function(e) {
        e.preventDefault();
        $('#modalItem').modal('hide')

        $collectionHolder.append($newFormModal.html());
        $collectionHolder.css('display', 'none');

        var tr = "<tr>" +
            "<td>" + $('#pedido_venda_items_' + index + '_produto option:selected').text()   + "</td>" +
            "<td>" + $('#pedido_venda_items_' + index + '_quantidade').val()   + "</td>" +
            "<td>R$ " + $('#pedido_venda_items_' + index + '_precoUnitario').val()   + "</td>" +
            "<td>" + $('#pedido_venda_items_' + index + '_percentualDesconto').val()   + "%</td>" +
            "<td>R$ " + $('#pedido_venda_items_' + index + '_total').val()   + "</td>" +
            '<td>' +
            '<a href="#" id="produto-remover-' + index + '" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i>'
            + "</td>" +
            "</tr>";

        $('#lista-produtos  > tbody:last-child').append(tr);

        $('#produto-remover-' + index).click(function(e) {
            $(this).parent().parent().remove();
            $('#pedido_venda_items_' + index).remove();
        });
        $('#modalItem-adicionar-btn').unbind('click');
    });

    // $newLinkLi.before($newFormLi);
}

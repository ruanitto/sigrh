$(document).ready(function () {

// Carregar as cidades do banco de dados e salvar na variavel vCidades em JSON
//    var vCidades = [
//        {id: 'SP', estado: 'Sao Paulo', cidades: [{id: 1, cidade: 'Sao Paulo'}, {id: 2, cidade: 'Santos'}, {id: 3, cidade: 'Praia Grande'}]},
//        {id: 'PR', estado: 'Parana', cidades: [{id: 7, cidade: 'Londrina'}, {id: 8, cidade: 'Cambé'}]}
//        
//    ];
    var vCidades = [];


    function carregaComboCidades(uf) {
        $("select[name=cidade]").html('<option>Selecione</option>');

        $.ajax({
            type: "POST",
            url: "/sig-rh/cidade/busca-cidades",
            async: false,
            data: {uf: uf},
            success: function (d) {
                vCidades = $.parseJSON(d.cidades);
            },
            dataType: "JSON"
        });

        $.each(vCidades, function (i, item) {
            $("<option></option>").val(item.id).html(item.cidade).appendTo($("select[name=cidade]"));
        });

//        $(vCidades).each(function (i, item) {
//            achou = false;
//            $(item.cidades).each(function (i2, item2) {
//                $("<option></option>").val(item2.id).html(item2.cidade).appendTo($("select[name=cidade]"));
//            });
//        });

    }
    
    function selecionaEstado(uf) {
        var achou = false;
        $("select[name=estado] option").each(function() {
            if ($(this).text() === uf) {
                $(this).attr("selected", "selected");
                achou =  true;
            }
        });
       return achou;
    }
    
    function addCidade(uf, nome) {
        $(vCidades).each(function (i, item) {
            if (item.id === uf) {
                achou = false;
                $(item.cidades).each(function (i2, item2) {
                    if (item2.cidade === nome) {
                        achou = true;
                        $("select[name=cidade]").val(item2.id);
                    }
                });
                if (!achou) {
                    // fazer uma chamada POST para um action e cadastrar essa cidade retornando o id via json
                    item.cidades.push({id: 999, cidade: nome});
                    carregaComboCidades(uf);
                    $("select[name=cidade]").val(999);
                }
            }
        });
        console.log(achou);
    }

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("input[name=endereco]").val("");
        $("input[name=bairro]").val("");
        $("select[name=cidade]").val("");
        $("input[name=estado]").val("");
//        $("#ibge").val("");
    }
    $("select[name=estado]").change(function () {
        carregaComboCidades($(this).val());
    });

    //Quando o campo cep perde o foco.
    $("input[name=cep]").change(function () {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep !== "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
//                        $("input[name=endereco]").val("...");
//                        $("input[name=bairro]").val("...");
//                        $("#cidade").val("...");
//                        $("#uf").val("...");
//                        $("#ibge").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("input[name=endereco]").val(dados.logradouro);
                        $("input[name=bairro]").val(dados.bairro);
                        alert(selecionaEstado(dados.uf));
                        
                        carregaComboCidades(dados.uf);
                        addCidade(dados.uf, dados.localidade);
                        //$("select[name=cidade]").val(dados.localidade);
//                        $("input[name=estado]").val(dados.uf);
                        //$("#ibge").val(dados.ibge);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });
});
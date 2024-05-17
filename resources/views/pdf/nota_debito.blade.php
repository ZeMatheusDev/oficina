
@php ini_set('memory_limit', '2G');
@endphp
<style>
    pre {
        white-space: pre-wrap;
        /* Since CSS 2.1 */
        white-space: -moz-pre-wrap;
        /* Mozilla, since 1999 */
        white-space: -pre-wrap;
        /* Opera 4-6 */
        white-space: -o-pre-wrap;
        /* Opera 7 */
        word-wrap: break-word;
        /* Internet Explorer 5.5+ */
    }

    .topModules {
        background-color: #D5D5D5;
        width: 100% !important;
        text-align: center;
        padding-bottom: 4px;
        padding-top: 6px;
        border-bottom: 1px  
        margin-left: -1px;
        /* height: 5em; */
        border-left: 1px  
        border-top: 1px  
        page-break-after: unset
    }

    .ql-size-large {
        font-size: 1.5rem
    }

    .ql-size-small {
        font-size: .75em;
    }

    @font-face {
        font-family: 'roboto_condensedregular';
        src: url('robotocondensed-regular-webfont.woff2') format('woff2'),
            url('robotocondensed-regular-webfont.woff') format('woff'),
            url('RobotoCondensed-Regular.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @page {
        margin: 2rem;
        margin-top: 8rem;
        margin-left: 2.65rem;
        margin-bottom: 8.65rem;
        /* padding: .5rem; */
        page-break-inside: avoid;
     
    }

    .contentModules img {
        max-width: 690px !important;
        max-height: 500px !important;
    }

    /* @page :first {
        margin: 2rem;
        margin-left: 2.65rem;
        margin-top: 8rem;
        margin-bottom: 8rem;
        /* page-break-before: always; */
    /* } */
    p {
        margin-top: 0px;
        margin-bottom: 0px;
   
    }
  

    table {
        page-break-inside: auto;
        font-size: 10pt;
               
    }

    header {
        position: fixed;
        left: 0px;
        right: 0px;
        height: 60px;
        margin-top: -150px;
     
      
    }

    footer {
        position: fixed;
        left: 0px;
        right: 0px;
        height: 60px;
        margin-top: 890px;
       
    }

    footer .page:after {
        content: counter(page, decimal);
    }

</style>

<body>
      
    <header>
        
        <p><br></p><p><br></p>
        <table style="border-collapse: collapse; width: 100%;font-size:10pt; border-style: solid; border-color: #000" border="1">
            <tbody>
                <tr>
                    <td rowspan="2" style="width: 25.0000%; border-color: #000; text-align: center;"><img src="{{ $EmpresaF?->logo_path }}" width="80" />
                    </td>
                    <td rowspan="2" style="width: 55.0000%; border-color: #000">
                        <div style="text-align: center;  border-color: #000"><strong>{{$EmpresaF?->name}}</strong></div>
                        <div style="text-align: center;  border-color: #000">{{$Empresa_Cabecalho?->numero}}</div>
                        <div style="text-align: center;  border-color: #000">{{$Empresa_Cabecalho?->situacao}}</div>
                        <div style="text-align: center;  border-color: #000">{{$Empresa_Cabecalho?->contato}}</div>
                        <div style="text-align: center; border-color: #000"><a href="mailto:{{$Empresa_Cabecalho?->email}}" style="color:black">{{$Empresa_Cabecalho?->email}} </a> | <a href="{{$Empresa_Cabecalho?->site}}" style="color:black" target="_blank">{{$Empresa_Cabecalho?->site}} </a></div>
                    </td>                
                    <td style="width: 20.0000%;text-align: center;">&nbsp; <strong> NOTA</strong> <br> {{$NumeroNota}}</td>
                </tr>
                <tr>
                    <td style="width: 20.0000%; text-align: center;">&nbsp; <strong> DATA DE</strong> <br> {{$Financeiro?->data_final}}</td>
                </tr>
            </tbody>
        </table>

    </header>

    <footer>
       
    </footer>
    <div style="margin-bottom: -3.6rem">
    
        <table style="border-collapse: collapse; width: 100%;  border-width: 1px; border-style: solid;" border-width="1">
            <tbody>
                <tr style="width: 100%; border-width: 20px">
                    <td colspan="2" style="width: 39.9768%; border-width: 1px; border-right-width: 0px; border-style: solid;"  >
                        <div style="text-align: center;"><strong>FATURA</strong></div>
                    </td>
                    <td colspan="2" style="width: 39.8601%; border-width: 1px; border-left-width: 0px; border-right-width: 0px; border-style: solid;">
                        <div style="text-align: center;"><strong>DUPLICATA</strong></div>
                    </td>
                    <td rowspan="2" style="width: 19.93%; border-width: 1px; border-style: solid;">
                        <div style="text-align: center;"><strong>VENCIMENTO</strong></div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 19.9301%; border-width: 1px; border-right-width: 0px; border-style: solid;">
                        <div style="text-align: center;"><strong>N&Uacute;MERO</strong></div>
                    </td>
                    <td style="width: 20.0000%; border-width: 1px; border-left-width: 0px; border-right-width: 0px; border-style: solid;">
                        <div style="text-align: center;"><strong>VALOR</strong></div>
                    </td>
                    <td style="width: 20.0000%; border-width: 1px; border-left-width: 0px; border-right-width: 0px; border-style: solid;">
                        <div style="text-align: center;"><strong>N&Uacute;MERO</strong></div>
                    </td>
                    <td style="width: 20.0000%; border-width: 1px; border-left-width: 0px; border-style: solid;">
                        <div style="text-align: center;"><strong>VALOR</strong></div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 19.9301%; text-align:center">{{$NumeroNota}}</td>
                    <td style="width: 20.0000%; text-align:center">R$ {{$ValorAjustado}}</td>
                    <td style="width: 20.0000%; text-align:center">{{$NumeroNota}}</td>
                    <td style="width: 20.0000%;text-align:center"> R$ {{$ValorAjustado}}</td>
                    <td style="width: 19.93%; text-align:center">{{$Financeiro?->data_prevista}}</td>
                </tr>
            </tbody>
        </table>
    
        <table style="border-collapse: collapse; width: 100%;  border-width: 1px; border-style: solid; margin-top:5px" border-width="1">
            <tbody>
                <tr>
                    <td style="width: 50.0000%;"><strong>Desconto de:</strong> {{$Financeiro?->desconto}}</td>
                    <td style="width: 50.0000%;"><strong>At&eacute;</strong>: {{$Financeiro?->desconto_ate}}</td>
                </tr>
                <tr>
                    <td style="width: 70.0000%;"><strong>Condi&ccedil;&atilde;o de Pagamento:</strong> {{$Financeiro?->condicao}}</td>
                    <td style="width: 30.0000%;"></td>
                </tr>
            </tbody>
        </table>
   
        <table style="border-collapse: collapse; width: 100%;  border-width: 1px; border-style: solid; margin-top:5px" border-width="1">
            <tbody>
                <tr style="">
                    <td style="width: 20%; padding: 3px;"><strong>Nome:</strong></td>
                    <td colspan="3" style="width: 80%; padding: 3px;">{{ $Cliente?->razao_social }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; padding: 3px;"><strong>Endere&ccedil;o:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->endereco }}</td>
                    <td style="width: 20%; padding: 3px;" ><strong>CEP:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->cep }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; padding: 3px;"><strong>Munic&iacute;pio Pra&ccedil;a de:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->cidade }}</td>
                    <td style="width: 20%; padding: 3px;"><strong>Estado:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->estado }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; padding: 3px;"><strong>CNPJ (M.F.):</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->cnpj_cpf }}</td>
                    <td style="width: 20%; padding: 3px;"><strong>Inscri&ccedil;&atilde;o Estadual:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->ins_estadual }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; padding: 3px;"><strong>End./ Cobran&ccedil;a:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->endereco_cobranca }}</td>
                    <td style="width: 20%; padding: 3px;"><strong>CEP:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->cep_cobranca }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; padding: 3px;"><strong>Municipio:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->cidade_cobranca }}</td>
                    <td style="width: 20%; padding: 3px;"><strong>Estado:</strong></td>
                    <td style="width: 30%; padding: 3px;">{{ $Cliente?->estado_cobranca }}</td>
                </tr>               
            </tbody>
        </table>      
        <table style="border-collapse: collapse; width: 100%;  border-width: 1px; border-style: solid; margin-top:5px">
            <tbody>
                <tr>
                    <td style="width: 20.7832%;"><strong>Valor por extenso:</strong></td>
                    <td style="width: 80.2168%;">{{$Extenso}}</td>
                </tr>
            </tbody>
        </table>
       
        <table style="border-collapse: collapse; width: 100%;  border-width: 1px; border-style: solid; border-color: #000;  margin-top:5px" border="1">
            <thead>
                <tr>
                    <td style="width: 75.2295%; padding: 5px;">
                        <div style="text-align: left;">&nbsp; <strong>DESCRI&Ccedil;&Atilde;O DOS SERVI&Ccedil;OS</strong></div>
                    </td>
                    <td style="width: 13.5508%;">
                        <div style="text-align: center;"><strong>UNIT&Aacute;RIO</strong></div>
                    </td>
                    <td style="width: 11.1578%;">
                        <div style="text-align: center;"><strong>TOTAL</strong></div>
                    </td>
                </tr>
            </thead>
                <tbody>
                @foreach ($Controle as $data)
                <tr>
                    <td style="width: 75.2295%;">{{$data->servico}}</td>
                    <td style="width: 13.5508%; text-align: center;">{{$data->qtd}}x R$ {{number_format($data->valor,2,",",".")}}</td>
                    <td style="width: 11.1578%; text-align: center;">R$ {{$data->total}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="width: 88.7256%;">
                        <div style="text-align: center;"><strong>TOTAL</strong></div>
                    </td>
                    <td style="width: 11.1578%; text-align: center;">R$ {{$ValorAjustado}}</td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%; font-size:10pt; border-collapse: collapse; border-width: 1px; border-style: solid; margin-top:5px;">
            <tbody>
                <tr>
                    <td style="width: 100.0000%;padding: 5px;  border-width: 1px; border-style: solid;"><b>DESCRIÇÃO<b></td>
                </tr>
                <tr>
                    <td style="width: 100.0000%; text-align:justify ">{{$FinanceiroNF->descricao_servico}}</td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%; font-size:10pt; border-collapse: collapse; border-width: 1px; border-style: solid; margin-top:5px;">
            <tbody>
                <tr>
                    <td colspan="2" style="width: 95%; font-size:8pt;  border-width: 1px; border-style: solid;"><strong>RECEBI(EMOS) DE {{$EmpresaF?->name}} A NOTA DE DÉBITO DE PRESTAÇÃO DE</strong></td>
                    <td rowspan="3" style="width: 24.9417%; border-width: 1px; border-style: solid; text-align:center"><strong style="margin-left:-125px">NOTA</strong><br><br><br>  {{$NumeroNota}} <br><br><br></td>
                </tr>
                <tr>
                    <td style="width: 25.0000%; border-width: 1px; border-style: solid;">
                        <div style="text-align: center;"><strong>DATA DO RECEBIMENTO</strong></div>
                    </td>
                    <td style="width: 49.8834%; text-align: center; border-width: 1px; border-style: solid;"><strong>INDENTIFICAÇÃO E ASSINATURA DO</strong></td>
                </tr>
                <tr>
                    <td style="width: 25.0583%; border-width: 1px; border-style: solid;"><br></td>
                    <td style="width: 49.8834%;"><br><br><br></td>
                </tr>
            </tbody>
        </table>       
    </div>
  
</body>

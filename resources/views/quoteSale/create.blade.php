@extends('layouts.appAdmin2')

@section('openQuote')
    menu-open
@endsection

@section('activeQuote')
    active
@endsection

@section('activeCreateQuote')
    active
@endsection

@section('title')
    Cotizaciones
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/typehead/typeahead.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.css') }}">
    <!-- Images -->
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }

        .input-group .select2-container {
            flex: 1 1 auto;       /* que ocupe el espacio disponible */
            width: 1% !important; /* que no se expanda a 100% */
        }
        .input-group .select2-selection {
            height: 100% !important; /* que se ajuste a la altura del input-group */
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Cotizaciones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nuevo cotización</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('quoteSale.index') }}"><i class="fa fa-key"></i> Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="materials" value="{{ json_encode($array) }}">

    <form id="formCreate" class="form-horizontal" data-url="{{ route('quoteSale.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">DATOS GENERALES</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="descriptionQuote">Descripción general de cotización </label>
                                <input type="text" id="descriptionQuote" onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="description">Código de cotización </label>
                                <input type="text" id="codeQuote" readonly value="{{ $codeQuote }}" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4" id="sandbox-container">
                                <label for="date_quote">Fecha de cotización </label>
                                <div class="input-daterange" id="datepicker">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_quote" name="date_quote">
                                </div>
                            </div>
                            <div class="col-md-4" id="sandbox-container">
                                <label for="date_end">Válido hasta </label>
                                <div class="input-daterange" id="datepicker2">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_validate" name="date_validate">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="paymentQuote">Forma de pago </label>
                                {{--<input type="hidden" onkeyup="mayus(this);" name="way_to_pay" class="form-control form-control-sm">--}}
                                <select id="paymentQuote" name="payment_deadline" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $paymentDeadlines as $paymentDeadline )
                                        <option value="{{ $paymentDeadline->id }}">{{ $paymentDeadline->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="description">Tiempo de entrega </label>
                                <div class="input-group input-group-sm mb-3">
                                    <input type="number" id="timeQuote" step="1" min="0" name="delivery_time" class="form-control form-control-sm">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2"> DIAS</span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <label for="customer_id">Cliente</label>
                                <div class="input-group input-group-sm">
                                    <select id="customer_id" name="customer_id" class="form-control select2bs4">
                                        <option></option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="btn-add-customer">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="contact_id">Contacto </label>
                                <select id="contact_id" name="contact_id" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="observations">Observaciones </label>
                                <textarea class="textarea_observations" id="observations" name="observations" placeholder="Place some text here"
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            </div>

                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        {{--<div class="row">
            <div class="col-md-4 offset-md-4 col-sm-4 offset-sm-4">
                <button type="button" id="btn-addEquipment" class="btn btn-block bg-gradient-primary">Nuevo equipo <i class="fas fa-plus-circle"></i></button>
                <br>
            </div>
        </div>--}}

        <div class="row" id="body-equipment">
            <div class="col-md-12">
                <div class="card card-success" data-equip="asd">
                    <div class="card-header">
                        <h3 class="card-title">COTIZACIÓN</h3>

                        <div class="card-tools">
                            <a data-confirm class="btn btn-primary btn-sm" data-toggle="tooltip" title="Confirmar" style="">
                                <i class="fas fa-check-square"></i> Confirmar cotización
                            </a>
                            <a class="btn btn-warning btn-sm" data-saveEquipment="" style="display:none" data-toggle="tooltip" title="Guardar cambios">
                                <i class="fas fa-check-square"></i> Guardar cambios
                            </a>
                            {{--<a class="btn btn-danger btn-sm" data-deleteEquipment="" style="display:none" data-toggle="tooltip" title="Quitar">
                                <i class="fas fa-check-square"></i> Eliminar cotización
                            </a>--}}
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <input type="hidden" name="" data-utilityEquipment value="{{ $utility->value }}">
                            <input type="hidden" name="" data-rentEquipment value="{{ $rent->value }}">
                            <input type="hidden" name="" data-letterEquipment value="{{ $letter->value }}">
                            <input type="hidden" name="" data-letterEquipment id="igv" value="{{ $igv }}">

                            <div class="col-md-12">
                                <label for="description">Detalles de la cotización</label>
                                <textarea class="textarea_edit" data-detailequipment placeholder="Place some text here"
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            </div>
                        </div>

                        <div class="card card-warning collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">PRODUCTOS</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Seleccionar producto <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control consumable_search" data-consumable style="width:100%" name="consumable_search"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-cantidad class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addConsumable class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div data-bodyConsumable>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <strong>Descripción</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>Unidad</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>Cantidad</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>V/U</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>P/U</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <strong>IMPORTE</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>Acción</strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- /.card -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        @can('showPrices_quote')
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">

            </div>
            <!-- /.col -->
            <div class="col-6">
                <p class="lead">Resumen de Cotización</p>

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">DESCUENTO (-): </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="descuento" class="align-right">0.00</span></td>
                        </tr>
                        <tr>
                            <th style="width:50%">GRAVADA: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="gravada" class="align-right">0.00</span></td>
                        </tr>
                        <tr>
                            <th style="width:50%">IGV {{ $igv }}%: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="igv_total" class="align-right">0.00</span></td>
                        </tr>
                        <tr>
                            <th style="width:50%">TOTAL: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="total_importe" class="align-right">0.00</span></td>
                        </tr>
                    </table>
                </div>

                {{--<div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Total S/IGV: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="subtotal">0.00</span></td>
                        </tr>
                        <tr>
                            <th>Total C/IGV: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="total">0.00</span></td>
                        </tr>
                        <tr>
                            <th style="width:50%">Total+Utilidad S/IGV: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="subtotal_utility">0.00</span></td>
                        </tr>
                        <tr>
                            <th style="width:50%">Total+Utilidad C/IGV: </th>
                            <td>{{ ($currency == 'pen') ?'PEN' : 'USD' }} <span id="total_utility">0.00</span></td>
                        </tr>
                    </table>
                </div>--}}
            </div>
            <!-- /.col -->
        </div>
        @endcan

        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar cotización</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>

    <div id="modalAddMaterial" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ingresar dimensiones o cantidad</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3" id="length_material">
                            <label class="col-sm-12 control-label" for="material_length"> Largo </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_length" name="material_length" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-3" id="width_material">
                            <label class="col-sm-12 control-label" for="material_width"> Ancho </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_width" name="material_width" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-3" id="quantity_material">
                            <label class="col-sm-12 control-label" for="material_quantity"> Cantidad </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_quantity" name="material_quantity" class="form-control" readonly />
                            </div>
                        </div>
                        @can('showPrices_quote')
                        <div class="col-md-3" id="price_material">
                            <label class="col-sm-12 control-label" for="material_price"> Precio C/IGV </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_price" name="material_price" class="form-control" readonly />
                            </div>
                        </div>
                        @endcan
                    </div>
                    <br>
                    <div class="row" id="presentation">

                        <div class="col-md-3">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="fraction" checked name="presentation" value="fraction">
                                <label for="fraction">Fraccionada
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-success d-inline">
                                <input type="radio" id="complete" name="presentation" value="complete">
                                <label for="complete">Completa
                                </label>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3" id="length_entered_material">
                            <label class="col-sm-12 control-label" for="material_length_entered"> Ingresar largo </label>

                            <div class="col-sm-12">
                                <input type="number" id="material_length_entered" name="material_length_entered" class="form-control" placeholder="0.00" min="0" value="" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                            </div>
                        </div>
                        <div class="col-md-3" id="width_entered_material">
                            <label class="col-sm-12 control-label" for="material_width_entered"> Ingresar ancho </label>

                            <div class="col-sm-12">
                                <input type="number" id="material_width_entered" name="material_width_entered" class="form-control" placeholder="0.00" min="0" value="" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                            </div>
                        </div>
                        <div class="col-md-3" id="quantity_entered_material">
                            <label class="col-sm-12 control-label" for="material_quantity_entered"> Ingresar cantidad </label>

                            <div class="col-sm-12">
                                <input type="number" id="material_quantity_entered" name="material_quantity_entered" class="form-control" placeholder="0.00" min="0" value="" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="btnCalculate"> &nbsp; </label>
                            <button type="button" id="btnCalculate" class="btn btn-block btn-outline-primary">Calcular <i class="fas fa-arrow-circle-right"></i></button>
                        </div>
                        <div class="col-md-2" id="percentage_entered_material">
                            <label class="col-sm-12 control-label" for="material_percentage_entered"> Porcentaje </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_percentage_entered" name="material_percentage_entered" class="form-control" readonly />
                            </div>
                        </div>
                        @can('showPrices_quote')
                        <div class="col-md-2" id="price_entered_material">
                            <label class="col-sm-12 control-label" for="material_price_entered"> Total </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_price_entered" name="material_price_entered" class="form-control" readonly />
                            </div>
                        </div>
                        @endcan
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-addMaterial" class="btn btn-outline-primary">Agregar</button>
                </div>

            </div>
        </div>
    </div>

    <template id="template-consumable">
        <div class="row">
            {{-- Descripcion --}}
            <div class="col-md-5">
                <div class="form-group">
                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-consumableDescription readonly>
                    <input type="hidden" data-consumableId>
                    <input type="hidden" data-descuento>
                    <input type="hidden" data-type_promotion>
                </div>
            </div>
            {{-- Unidad --}}
            <div class="col-md-1">
                <div class="form-group">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-consumableUnit readonly>
                    </div>
                </div>
            </div>
            {{-- Cantidad --}}
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" oninput="calculateTotalC(this);" data-consumableQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                </div>
            </div>
            {{-- Valor Unitario --}}
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableValor step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                </div>
            </div>
            {{-- Precio Unitario --}}
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumablePrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                </div>
            </div>
            {{-- Importe --}}
            <div class="col-md-2">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableImporte step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" data-deleteConsumable class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
            </div>
        </div>
    </template>

    <!-- Modal -->
    <div class="modal fade" id="promotionModal" tabindex="-1" role="dialog" aria-labelledby="promotionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Promociones disponibles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="promotion-content">
                    <!-- Aquí se cargan dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cliente -->
    <div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-labelledby="modalCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalCustomerLabel">Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="formCreateCustomer" class="form-horizontal" data-url="{{ route('customer.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-12 col-form-label">RUC <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" class="form-control" name="ruc" placeholder="Ejm: 1234678901">
                            </div>

                            <div class="col-md-2">
                                <label class="col-12 col-form-label">Extranjero <span class="right badge badge-danger">(*)</span></label>
                                <input id="btn-grouped" type="checkbox" name="special" data-bootstrap-switch data-off-color="danger" data-on-text="SI" data-off-text="NO" data-on-color="success">
                            </div>

                            <div class="col-md-6">
                                <label class="col-12 col-form-label">Razon Social <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" class="form-control" onkeyup="mayus(this);" name="business_name" placeholder="Ejm: Edesce EIRL">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-12 col-form-label">Direccion</label>
                                <input type="text" class="form-control" onkeyup="mayus(this);" name="address" placeholder="Ejm: Jr Union">
                            </div>

                            <div class="col-md-6">
                                <label class="col-12 col-form-label">Ubicacion</label>
                                <input type="text" class="form-control" onkeyup="mayus(this);" name="location" placeholder="Ejm: Moche">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btn-submit-customer" class="btn btn-outline-success">Guardar</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
    <script>
        $(function () {

            $('.textarea_observations').summernote({
                lang: 'es-ES',
                placeholder: 'Ingrese los detalles',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });
            $('.textarea_edit').summernote({
                lang: 'es-ES',
                placeholder: 'Ingrese los detalles',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });
            //Initialize Select2 Elements
            $('#customer_id').select2({
                placeholder: "Selecione cliente",
                theme: 'bootstrap4',
                width: 'resolve'
            });
            $('#contact_id').select2({
                placeholder: "Selecione contacto",
            });
            $('#paymentQuote').select2({
                placeholder: "Selecione forma de pago",
            });

            $('.unitMeasure').select2({
                placeholder: "Seleccione unidad",
            });

            $('#date_quote').attr("value", moment().format('DD/MM/YYYY'));

            $('#date_validate').attr("value", moment().add(5, 'days').format('DD/MM/YYYY'));

            $('#sandbox-container .input-daterange').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                language: "es",
                multidate: false,
                autoclose: true
            });
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>

    <script src="{{ asset('js/quoteSale/create.js') }}?v={{ time() }}"></script>
@endsection

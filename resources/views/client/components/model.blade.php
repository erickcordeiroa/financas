<!-- Modal Income -->
<div class="modal fade" id="modalIncome" tabindex="-1" role="dialog" aria-labelledby="newIncome" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newIncome"><i class="fas fa-calendar-check"></i> Nova Receita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="income">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label for="description" class="text-gray"><i class="fas fa-book-open"></i>
                                    Descrição:</label>
                                <input type="text" name="description" required class="form-control"
                                    placeholder="Ex: Aluguel">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="value" class="text-gray"><i class="far fa-money-bill-alt"></i>
                                    Valor:</label>
                                <input type="text" name="value" required class="form-control" placeholder="0,00"
                                    maxlength="22">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="due_at" class="text-gray"><i class="far fa-calendar-alt"></i>
                                    Data:</label>
                                <input type="date" name="due_at" required class="form-control"
                                    placeholder="dd/mm/aaaa">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="wallet" class="text-gray"><i class="fas fa-wallet"></i> Data:</label>
                                <select name="wallet" required class="form-control">
                                    <option value="#"> Minha Carteira </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="category" class="text-gray"><i class="fas fa-filter"></i>
                                    Categoria:</label>
                                <select name="category" required class="form-control">
                                    <option value="#"> Minha Categoria </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-gray"><i class="fas fa-exchange-alt"></i> Repetição:</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="single"
                                                name="repeat_when" value="single" checked>
                                            <label for="single" class="custom-control-label"
                                                data-checkbox="single">Única</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="fixed"
                                                name="repeat_when" value="fixed">
                                            <label for="fixed" class="custom-control-label"
                                                data-checkbox="fixed">Fixa</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="enrollment"
                                                name="repeat_when" value="enrollment">
                                            <label for="enrollment" class="custom-control-label"
                                                data-checkbox="enrollment">Parcelada</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <label class="repeate_item repeate_item_fixed col-12" style="display: none">
                                        <select name="period" class="form-control">
                                            <option value="month">&ofcir; Mensal</option>
                                            <option value="year">&ofcir; Anual</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="repeate_item repeate_item_enrollment col-12" style="display: none">
                                        <input class="radius form-control" type="number" min="2" max="420"
                                            placeholder="1 parcela" name="enrollments" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div> <!-- ROW -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success"><i class="fas fa-check"></i> LANÇAR RECEITAS</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Expense -->
<div class="modal fade" id="modalExpense" tabindex="-1" role="dialog" aria-labelledby="newExpense"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newExpense"><i class="fas fa-calendar-times"></i> Nova Despesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="expense">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label for="description" class="text-gray"><i class="fas fa-book-open"></i>
                                    Descrição:</label>
                                <input type="text" name="description" required class="form-control"
                                    placeholder="Ex: Aluguel">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="value" class="text-gray"><i class="far fa-money-bill-alt"></i>
                                    Valor:</label>
                                <input type="text" name="value" required class="form-control" placeholder="0,00"
                                    maxlength="22">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="due_at" class="text-gray"><i class="far fa-calendar-alt"></i>
                                    Data:</label>
                                <input type="date" name="due_at" required class="form-control"
                                    placeholder="dd/mm/aaaa">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="wallet" class="text-gray"><i class="fas fa-wallet"></i> Data:</label>
                                <select name="wallet" required class="form-control">
                                    <option value="#"> Minha Carteira </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="category" class="text-gray"><i class="fas fa-filter"></i>
                                    Categoria:</label>
                                <select name="category" required class="form-control">
                                    <option value="#"> Minha Categoria </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-gray"><i class="fas fa-exchange-alt"></i> Repetição:</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="single"
                                                name="repeat_when" value="single" checked>
                                            <label for="single" class="custom-control-label"
                                                data-checkbox="single">Única</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="fixed"
                                                name="repeat_when" value="fixed">
                                            <label for="fixed" class="custom-control-label"
                                                data-checkbox="fixed">Fixa</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="enrollment"
                                                name="repeat_when" value="enrollment">
                                            <label for="enrollment" class="custom-control-label"
                                                data-checkbox="enrollment">Parcelada</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <label class="repeate_item repeate_item_fixed col-12" style="display: none">
                                        <select name="period" class="form-control">
                                            <option value="month">&ofcir; Mensal</option>
                                            <option value="year">&ofcir; Anual</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="repeate_item repeate_item_enrollment col-12" style="display: none">
                                        <input class="radius form-control" type="number" min="2" max="420"
                                            placeholder="1 parcela" name="enrollments" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div> <!-- ROW -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger"><i class="fas fa-check"></i> LANÇAR DESPESA</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        /*
         * FROM CHECKBOX
         */
        $("[data-checkbox]").click(function(e) {
            var checkbox = $(this);
            let checkboxVal = checkbox.data('checkbox');

            if (checkboxVal == 'single') {
                $('.repeate_item').slideUp();
                $('.repeate_item_fixed').css('style', 'none');
                $('.repeate_item_enrollment').css('style', 'none');
            }

            if (checkboxVal == 'fixed') {
                $('.repeate_item').slideUp();
                $('.repeate_item_enrollment').css('style', 'none');
                $('.repeate_item_fixed').slideDown();

            }

            if (checkboxVal == 'enrollment') {
                $('.repeate_item').slideUp();
                $('.repeate_item_fixed').css('style', 'none');
                $('.repeate_item_enrollment').slideDown();
            }
        });
    </script>
@endsection

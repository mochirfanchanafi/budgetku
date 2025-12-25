<div id="index">
    <div class="row">
        <div class="col col-12 col-md-12 col-sm-12 col-xs-12 col-xs-12">
            <div class="card shadow-none border h-100">
                <div class="card-body">
                    <!-- filters -->
                        @include($menuparam['viewparent'].'.forms.filter-form')
                    <!-- ======= -->
                    <!-- tables -->
                        @include($menuparam['viewparent'].'.tables.index-table')
                    <!-- ======= -->
                </div>
            </div>
        </div>
    </div>
</div>
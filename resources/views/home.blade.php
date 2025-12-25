<div class="row">
    <div class="col col-12 col-md-12 col-sm-12">
        @if($errors->any())
            <div class="alert alert-danger text-light" role="alert">
                {{$errors->first()}}
            </div>
        @endif
    </div>
</div>
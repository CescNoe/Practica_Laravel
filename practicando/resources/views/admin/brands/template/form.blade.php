<div class="row">
    <div class="col-8">
        <div class="form-group">
            {!! Form::label('name', 'Nombre') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre de la marca', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Descripción') !!}
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'placeholder' => 'Agregue una descripción',
                'rows' => 3,
            ]) !!}
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <div id="imageButton" style="width: 100%; text-align: center; margin-bottom: 10px;">
                <img id="imagePreview"
                    src="{{ empty($brand->logo) ? asset('storage/brand_logo/no-image-icon-6.png') : asset($brand->logo) }}"
                    alt="Vista previa de la imagen" style="width: 100%;height:180px;cursor: pointer;">
                <p style="font-size:12px">Haga click para seleccionar una imagen</p>
            </div>

        </div>
        <div class="form-group">
            {!! Form::label('logo', 'Logo') !!}
            {!! Form::file('logo', ['class' => 'form-control-file d-none', 'accept' => 'image/*', 'id' => 'imageInput']) !!}
        </div>
    </div>
</div>

<script>
    $('#imageInput').change(function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });

    $('#imageButton').click(function() {
        $('#imageInput').click();
    });
</script>

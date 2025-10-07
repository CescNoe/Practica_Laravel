<div class="row">
    <div class="col-8">
        <div class="form-group">
            {!! Form::label('name', 'Nombre') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre del vehículo', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('brand', 'Marca') !!}
            {!! Form::text('brand', null, ['class' => 'form-control', 'placeholder' => 'Marca del vehículo', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('model', 'Modelo') !!}
            {!! Form::text('model', null, ['class' => 'form-control', 'placeholder' => 'Modelo del vehículo', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('year', 'Año') !!}
            {!! Form::number('year', null, ['class' => 'form-control', 'placeholder' => 'Año del vehículo', 'required']) !!}
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
</div>
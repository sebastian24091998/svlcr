<div>
    
    <style></style>

    <div class="row layout-top-spacing">
        <div class="col-sm-12 col-md-9">
            
            <!-- detalles -->
            @include('livewire.pos.partials.detail')
        </div>
        <div class="col-sm-12 col-md-3">
            <!-- total -->
            @include('livewire.pos.partials.total')
            <!-- denominations -->
            @include('livewire.pos.partials.coins')
            
        </div>
    </div>
    @include('livewire.pos.partials.QR')
</div>

@include('livewire.pos.scripts.shortcuts')
@include('livewire.pos.scripts.general')
@include('livewire.pos.scripts.events')


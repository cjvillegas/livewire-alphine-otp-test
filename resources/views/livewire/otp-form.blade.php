<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $name = 'Shit';
}; ?>

<div>
    <h1>{{ $name }}</h1>
</div>

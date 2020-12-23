<section class="block-explorer-wrapper header-large bg-bottom-center" id="welcome">
    <div class="block-explorer text">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12 align-self-center">
                    <h1>Gulden Blockchain Explorer</h1>
                </div>
                @if($lastBlock !== null)
                    <div class="offset-lg-3 col-lg-6">
                        <p>Up To Block <a href="{{ route('block', ['block' => $lastBlock->height]) }}">{{ $lastBlock->height }}</a> <small>({{ $lastBlock->created_at->diffInSeconds(now()) }} seconds ago)</small></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('layouts.partials.search')
</section>

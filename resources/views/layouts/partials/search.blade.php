<section class="block-explorer-wrapper bg-bottom-center" id="welcome">
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
    <div class="search">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="input-wrapper">
                        <div class="input">
                            <form method="POST" action="{{ route('search') }}">
                                @csrf
                                <input aria-label="Search" type="text" name="query" placeholder="block, hash, transaction, etc...">
                                <button aria-label="Search submit" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

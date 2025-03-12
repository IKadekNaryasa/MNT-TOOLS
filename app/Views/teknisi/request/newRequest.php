<?php $this->extend('template/teknisiTemplate'); ?>
<?php $this->section('content'); ?>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <ul class="breadcrumbs ms-0">
                <li class="nav-home">
                    <a href="<?= base_url('teknisi/dashboard'); ?>">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-home">
                    <a href="<?= base_url('teknisi/request'); ?>">
                        request
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('teknisi/request/new'); ?>">New Request</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <?php foreach ($categories as $kt): ?>
                <div class=" col-md-3">
                    <div class="card card-stats card-primary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="icon-big text-center" id="jumlah-tersedia-<?= $kt['categoryId']; ?>">
                                        <?= $kt['jumlahTersedia']; ?>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <p class="card-category"><?= $kt['namaKategori']; ?></p>
                                    <h4 class="card-title text-end">
                                        <button class="btn btn-primary btn-sm add-to-cart"
                                            data-id="<?= $kt['categoryId']; ?>"
                                            data-nama="<?= $kt['namaKategori']; ?>"
                                            data-jumlah-tersedia="<?= $kt['jumlahTersedia']; ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <div class="page-category fixed-position">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Keranjang</h4>
                    </div>
                    <div class="card-body">
                        <form id="cart-form" method="POST" action="<?= base_url('teknisi/request/create'); ?>">
                            <div id="cart-container" class="d-flex flex-wrap">
                            </div>
                            <input type="hidden" name="cart_data" id="cart-data" value="">
                            <button type="submit" class="btn btn-success mt-3" id="submit-cart">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];

    function addToCart(id, nama) {
        const jumlahTersediaElem = document.getElementById(`jumlah-tersedia-${id}`);
        let jumlahTersedia = parseInt(jumlahTersediaElem.textContent);

        if (jumlahTersedia > 0) {
            jumlahTersedia--;
            jumlahTersediaElem.textContent = jumlahTersedia;

            cart.push({
                id,
                nama
            });
            renderCart();
        }
    }

    function removeFromCart(index) {
        const removedItem = cart[index];
        cart.splice(index, 1);

        const jumlahTersediaElem = document.getElementById(`jumlah-tersedia-${removedItem.id}`);
        let jumlahTersedia = parseInt(jumlahTersediaElem.textContent);
        jumlahTersedia++;
        jumlahTersediaElem.textContent = jumlahTersedia;

        renderCart();
    }

    function renderCart() {
        const cartContainer = document.getElementById('cart-container');
        const cartDataInput = document.getElementById('cart-data');
        cartContainer.innerHTML = '';

        cart.forEach((item, index) => {
            const button = document.createElement('button');
            button.className = 'btn btn-secondary m-2 btn-sm position-relative';
            button.type = 'button';
            button.textContent = item.nama;

            const closeButton = document.createElement('span');
            closeButton.className = 'position-absolute top-0 end-0 text-danger';
            closeButton.style.cursor = 'pointer';
            closeButton.innerHTML = '&times;';
            closeButton.onclick = () => removeFromCart(index);

            button.appendChild(closeButton);
            cartContainer.appendChild(button);
        });

        cartDataInput.value = JSON.stringify(cart);
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            addToCart(id, nama);
        });
    });

    document.getElementById('cart-form').addEventListener('submit', (e) => {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Keranjang kosong! Harap tambahkan item terlebih dahulu.');
        }
    });
</script>
<?php $this->endSection(); ?>c
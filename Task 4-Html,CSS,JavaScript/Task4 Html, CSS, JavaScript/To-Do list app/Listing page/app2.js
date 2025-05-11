document.addEventListener('DOMContentLoaded', function () {
    // Sample product data
    const products = [
        {
            id: 1,
            title: "Wireless Bluetooth Headphones",
            category: "electronics",
            price: 89.99,
            rating: 4.5,
            reviews: 124,
            image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: true
        },
        {
            id: 2,
            title: "Classic Cotton T-Shirt",
            category: "clothing",
            price: 19.99,
            rating: 4.2,
            reviews: 89,
            image: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: false
        },
        {
            id: 3,
            title: "Stainless Steel Water Bottle",
            category: "home",
            price: 24.95,
            rating: 4.8,
            reviews: 215,
            image: "https://images.unsplash.com/photo-1602143407151-7111542de6e8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: true
        },
        {
            id: 4,
            title: "Bestseller Novel",
            category: "books",
            price: 14.99,
            rating: 4.7,
            reviews: 342,
            image: "https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: false
        },
        {
            id: 5,
            title: "Yoga Mat",
            category: "sports",
            price: 29.99,
            rating: 4.3,
            reviews: 56,
            image: "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: false
        },
        {
            id: 6,
            title: "Smartphone",
            category: "electronics",
            price: 699.99,
            rating: 4.9,
            reviews: 478,
            image: "https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: true
        },
        {
            id: 7,
            title: "Denim Jeans",
            category: "clothing",
            price: 49.99,
            rating: 4.0,
            reviews: 67,
            image: "https://images.unsplash.com/photo-1473966968600-fa801b869a1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: false
        },
        {
            id: 8,
            title: "Coffee Maker",
            category: "home",
            price: 59.99,
            rating: 4.6,
            reviews: 189,
            image: "https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=500&q=60",
            isNew: true
        }
    ];

    // DOM Elements
    const productsContainer = document.getElementById('products-container');
    const categoryFilters = document.querySelectorAll('input[name="category"]');
    const ratingFilters = document.querySelectorAll('input[name="rating"]');
    const priceMinInput = document.getElementById('price-min');
    const priceMaxInput = document.getElementById('price-max');
    const minPriceDisplay = document.getElementById('min-price');
    const maxPriceDisplay = document.getElementById('max-price');
    const sortSelect = document.getElementById('sort-select');
    const resetFiltersBtn = document.getElementById('reset-filters');
    const shownCount = document.getElementById('shown-count');
    const totalCount = document.getElementById('total-count');

    // State
    let filteredProducts = [...products];
    let activeCategories = Array.from(categoryFilters).filter(c => c.checked).map(c => c.value);
    let activeRatings = [];
    let minPrice = 0;
    let maxPrice = 500;

    // Initialize
    function init() {
        renderProducts(products);
        updateProductCount(products.length, products.length);
        setupEventListeners();
    }

    // Render products
    function renderProducts(productsToRender) {
        productsContainer.innerHTML = '';

        if (productsToRender.length === 0) {
            productsContainer.innerHTML = '<div class="no-products">No products match your filters. Try adjusting your criteria.</div>';
            return;
        }

        productsToRender.forEach(product => {
            const productCard = createProductCard(product);
            productsContainer.appendChild(productCard);
        });
    }

    // Create product card HTML
    function createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card';

        // Calculate full and empty stars
        const fullStars = Math.floor(product.rating);
        const hasHalfStar = product.rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        let starsHTML = '';
        for (let i = 0; i < fullStars; i++) {
            starsHTML += '<i class="fas fa-star"></i>';
        }
        if (hasHalfStar) {
            starsHTML += '<i class="fas fa-star-half-alt"></i>';
        }
        for (let i = 0; i < emptyStars; i++) {
            starsHTML += '<i class="far fa-star"></i>';
        }

        card.innerHTML = `
            <div class="product-image">
                <img src="${product.image}" alt="${product.title}">
                ${product.isNew ? '<span class="new-badge">New</span>' : ''}
            </div>
            <div class="product-info">
                <div class="product-category">${product.category}</div>
                <h3 class="product-title">${product.title}</h3>
                <div class="product-price">$${product.price.toFixed(2)}</div>
                <div class="product-rating">
                    <div class="stars">${starsHTML}</div>
                    <div class="count">(${product.reviews})</div>
                </div>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        `;

        return card;
    }

    // Filter products based on current criteria
    function filterProducts() {
        filteredProducts = products.filter(product => {
            // Category filter
            if (activeCategories.length > 0 && !activeCategories.includes(product.category)) {
                return false;
            }

            // Price filter
            if (product.price < minPrice || product.price > maxPrice) {
                return false;
            }

            // Rating filter
            if (activeRatings.length > 0) {
                const productRating = Math.floor(product.rating);
                if (!activeRatings.includes(productRating.toString())) {
                    return false;
                }
            }

            return true;
        });

        // Sort products
        sortProducts();

        // Update UI
        renderProducts(filteredProducts);
        updateProductCount(filteredProducts.length, products.length);
    }

    // Sort products based on selected option
    function sortProducts() {
        const sortValue = sortSelect.value;

        switch (sortValue) {
            case 'price-asc':
                filteredProducts.sort((a, b) => a.price - b.price);
                break;
            case 'price-desc':
                filteredProducts.sort((a, b) => b.price - a.price);
                break;
            case 'rating':
                filteredProducts.sort((a, b) => b.rating - a.rating);
                break;
            case 'popularity':
                filteredProducts.sort((a, b) => b.reviews - a.reviews);
                break;
            case 'newest':
                filteredProducts.sort((a, b) => b.isNew - a.isNew);
                break;
            default: // 'featured' or others
                // Keep original order or implement featured logic
                break;
        }
    }

    // Update product count display
    function updateProductCount(shown, total) {
        shownCount.textContent = shown;
        totalCount.textContent = total;
    }

    // Update price range display
    function updatePriceDisplay() {
        minPriceDisplay.textContent = `$${minPrice}`;
        maxPriceDisplay.textContent = `$${maxPrice}`;
    }

    // Setup event listeners
    function setupEventListeners() {
        // Category filter change
        categoryFilters.forEach(filter => {
            filter.addEventListener('change', function () {
                activeCategories = Array.from(categoryFilters)
                    .filter(c => c.checked)
                    .map(c => c.value);
                filterProducts();
            });
        });

        // Rating filter change
        ratingFilters.forEach(filter => {
            filter.addEventListener('change', function () {
                activeRatings = Array.from(ratingFilters)
                    .filter(r => r.checked)
                    .map(r => r.value);
                filterProducts();
            });
        });

        // Price range sliders
        priceMinInput.addEventListener('input', function () {
            minPrice = parseInt(this.value);
            if (minPrice > maxPrice) {
                minPrice = maxPrice;
                this.value = minPrice;
            }
            updatePriceDisplay();
            filterProducts();
        });

        priceMaxInput.addEventListener('input', function () {
            maxPrice = parseInt(this.value);
            if (maxPrice < minPrice) {
                maxPrice = minPrice;
                this.value = maxPrice;
            }
            updatePriceDisplay();
            filterProducts();
        });

        // Sort select change
        sortSelect.addEventListener('change', function () {
            filterProducts();
        });

        // Reset filters
        resetFiltersBtn.addEventListener('click', function () {
            // Reset category filters
            categoryFilters.forEach(filter => {
                filter.checked = true;
            });
            activeCategories = Array.from(categoryFilters).map(c => c.value);

            // Reset rating filters
            ratingFilters.forEach(filter => {
                filter.checked = false;
            });
            activeRatings = [];

            // Reset price range
            minPrice = 0;
            maxPrice = 500;
            priceMinInput.value = minPrice;
            priceMaxInput.value = maxPrice;
            updatePriceDisplay();

            // Reset sort
            sortSelect.value = 'featured';

            // Apply filters
            filterProducts();
        });
    }

    // Initialize the app
    init();
});
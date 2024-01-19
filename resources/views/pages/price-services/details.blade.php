@extends('layouts.app',['title' => 'Price', 'active' => 'prices'])

@section('content')
    @php
        $sub_active = request()->segment(2);
    @endphp
    <div id="app">
        <div v-if="isLoading" class="loader"><i class="fa fa-spin fa-spinner"></i></div>
        <section class="section-categories bg-boll">
            <div class="container">
                <ul class="filter-by-category d-flex justify-content-center mb-0 pt-16 pb-16">
                    <li v-for="(item, i) in services" :key="i">
                        <a @click.prevent="switchService(item)"
                           :href="`{{ route('pricing.details', ['service' => '']) }}${item.title}`"
                           :class="{ active: item.slug === activeService }">
                            @{{ item.title }}
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        <!-- section our prices  -->
        <section v-if="!isLoading" class="section-category-banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="banner-content d-md-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img :src="getServiceBySlug(activeService).icon" :alt="getServiceBySlug(activeService).title">
                            </div>
                            <div class="flex-grow-1 ps-md-4">
                                <h1 class="mb-md-3 mb-2 fw-bolder">@{{ getServiceBySlug(activeService).title }}</h1>
                                <p class="mb-md-3 mb-2">@{{ getServiceBySlug(activeService).description }}</p>
                                <p class="color-primary fw-lighter">
                                    @{{ getServiceBySlug(activeService).tagsString }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section-tabs">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <ul class="filter-by-category nav">
                            <li class="nav-item">
                                <a
                                    class="nav-link link-secondary active"
                                    id="PriceList-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#priceListTab"
                                    href="#">Price list</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link link-secondary"
                                   id="serviceDetails-tab"
                                   data-bs-toggle="tab"
                                   data-bs-target="#serviceDetailsTab"
                                   href="#">Service Details</a>
                            </li>
                        </ul>
                        <div class="main b-radius-16">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="tab-content" id="tabContent">
                                        <div class="tab-pane fade show active"
                                             id="priceListTab"
                                             role="tabpanel"
                                             aria-labelledby="PriceList-tab">
                                            <ul class="services-sub-menu" v-if="getServiceBySlug(activeService).subCategories && getServiceBySlug(activeService).subCategories.length > 1">
                                                <li v-for="(item, i) in getServiceBySlug(activeService).subCategories" :key="i">
                                                    <a :class="{active: activeSubCategory === item.slug}" href="#"
                                                       @click.prevent="selectSubcategory(item.slug)">@{{ item.name }}</a>
                                                </li>
                                            </ul>
                                            <div class="table">
                                                <table class="table" v-if="activeSubCategory">
                                                    <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total price</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(product) in getProductsBySubcategory(activeSubCategory)"
                                                        :key="product.id">
                                                        <td>@{{ product.product_name }}</td>
                                                        <td>@{{ getServiceBySlug(activeService).priceSymbol + product.product_price }}</td>
                                                        <td>
                                                            <div class="counter">
                                                                <div @click.prevent="decrementCount(product)"
                                                                     class="decrement-count">
                                                                    <i class="fas fa-minus"></i>
                                                                </div>
                                                                <div class="total-count">@{{ product.quantity }}</div>
                                                                <div @click.prevent="incrementCount(product)"
                                                                     class="increment-count">
                                                                    <i class="fal fa-plus"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>@{{ getServiceBySlug(activeService).priceSymbol + (product.product_price * product.quantity).toFixed(2) }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="serviceDetailsTab" role="tabpanel"
                                             aria-labelledby="serviceDetails-tab">
                                            <div class="services-details" v-if="activeServiceDetails">
                                                <div class="border-bottom d-flex">
                                                    <i class="icon icon-search-service"></i>
                                                    <div class="ms-2">
                                                        <h6>Service Overview</h6>
                                                        <p v-html="activeServiceDetails.overview"></p>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4" v-if="activeServiceDetails.options">
                                                    <i class="icon icon-suitable"></i>
                                                    <div class="ms-2">
                                                        <h6>Options</h6>
                                                        <div v-html="activeServiceDetails.options"></div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4">
                                                    <i class="icon icon-suitable"></i>
                                                    <div class="ms-2">
                                                        <h6>Suitable for</h6>
                                                        <div v-html="activeServiceDetails.suitable"></div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4">
                                                    <i class="icon icon-dont-include"></i>
                                                    <div class="ms-2">
                                                        <h6>Don’t Include</h6>
                                                        <div v-html="activeServiceDetails.not_include"></div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4">
                                                    <i class="icon icon-collection"></i>
                                                    <div class="ms-2">
                                                        <h6>Prepare for Collection</h6>
                                                        <div v-html="activeServiceDetails.collection"></div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <i class="icon icon-laundry-delivery"></i>
                                                    <div class="ms-2">
                                                        <h6>Laundry Delivery</h6>
                                                        <div v-html="activeServiceDetails.delivery"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="aside">
                                        <h4>@{{ selectedServices.length }} Service Selected</h4>
                                        <div class="description border-bottom">
                                            <p>Do I need to list every item? <a href="#" class="color-brand">Learn
                                                    more</a></p>
                                        </div>

                                        <div v-for="(item, i) in selectedServices" class="services-list border-bottom">
                                            <div class="d-flex">
                                                <img :src="getServiceBySlug(item.service).icon" class="w-40" :alt="getServiceBySlug(item.service).title">
                                                <h3>@{{ getServiceBySlug(item.service).title }}</h3>
                                            </div>
                                            <div v-for="(priceItem, j) in item.products" :key="j">
                                                <div class="d-flex justify-content-between"
                                                     v-if="priceItem.quantity > 0">
                                                    <p>@{{ priceItem.product_name }}</p>
                                                    <p>@{{ getServiceBySlug(item.service).priceSymbol + (priceItem.quantity * priceItem.product_price) }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="border-bottom pb-3 pt-3">This is an estimated price, final price will
                                            be calculated after the laundry job is done.</p>
                                        <div
                                            class="estimated-price d-flex justify-content-between pt-3 pb-3 border-bottom">
                                            <p>Estimated price</p>
                                            <p>@{{ getServiceBySlug(activeService).priceSymbol +
                                                estimatedPrice.toFixed(2) }}</p>
                                        </div>

                                        <div class="text-center mt-40">
                                            <a class="btn btn-brand py-3 px-32 d-inline-block"
                                               @click="redirectToOtherView">Schedule an Order</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section-prices bg-light">
            <div class="container">
                <h2 class="mb-32 text-center fw-600">Up to 30% saving on prepaid packages</h2>
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="box b-radius-16 bg-white position-relative pb-60">
                            <div class="d-flex align-items-center">
                                <i class="icon icon-laundary-service"></i>
                                <div class="price-text">
                                    <h3 class="fw-600">5 Loads</h3>
                                    <h4 class="mt-2"><span class="fw-bold color-brand-orange fw-600">£15.80</span></h4>
                                </div>
                            </div>
                            <div class="off-price">£16.95/item</div>
                            <a href="#" title="View offer" class="view-offer">View offer</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="box b-radius-16 bg-white position-relative pb-60">
                            <div class="d-flex align-items-center">
                                <i class="icon icon-laundary-service"></i>
                                <div class="price-text">
                                    <h3 class="fw-600">10 Loads</h3>
                                    <h4 class="mt-2"><span class="fw-bold color-brand-orange fw-600">£14.90</span></h4>
                                </div>
                            </div>
                            <div class="off-price">£16.95/item</div>
                            <a href="#" title="View offer" class="view-offer">View offer</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="box b-radius-16 bg-white position-relative pb-60">
                            <div class="d-flex align-items-center">
                                <i class="icon icon-laundary-service"></i>
                                <div class="price-text">
                                    <h3 class="fw-600">20 Loads</h3>
                                    <h4 class="mt-2"><span class="fw-bold color-brand-orange fw-600">£13.95</span></h4>
                                </div>
                            </div>
                            <div class="off-price">£16.95/item</div>
                            <a href="#" title="View offer" class="view-offer">View offer</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section v-if="serviceQueAns" class="section section-prices">
            <div class="container text-center">
                <h2 class="mb-32 text-center fw-600">@{{ serviceQueAns.que }}</h2>
                <h3 class="fw-500">@{{ serviceQueAns.answer }}</h3>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    isLoading: true,
                    estimatedPrice: 0,
                    activeService: "wash",
                    activeSubCategory: "",
                    service: null,
                    activeServiceDetails: null,
                    serviceQueAns: null,
                    services: [
                        {
                            slug: "wash",
                            title: "Wash",
                            icon: "images/icons/trRO9sLvA1bXoKEZ6J8uGgGe3zz7kvQaPOxDAUNF.svg",
                            service_question: "Not sure how much you have?",
                            service_answer: "One load of 6kg is about: 7 shirts + 3 trousers + 7 underwear + 7 pairs of socks"
                        },
                    ],
                    selectedServices: [],
                }
            },
            mounted: function () {
                this.getData();
            },
            methods: {
                selectSubcategory(slug) {
                    this.activeSubCategory = slug;
                },

                getProductsBySubcategory(subcategorySlug) {
                    const activeService = this.getServiceBySlug(this.activeService);
                    return activeService.products.filter((product) => product.product_cat === subcategorySlug);
                },

                switchService(service) {
                    this.activeService = service.slug;
                    this.activeSubCategory = service.subCategories[0].slug;
                    this.activeServiceDetails = service.serviceDetails;
                    this.serviceQueAns = service.serviceQueAns;
                },
                getServiceBySlug(slug) {
                    return this.services.filter(function (item) {
                        return item.slug === slug;
                    })[0];
                },

                incrementCount(product) {
                    product.quantity++;
                    const existingService = this.selectedServices.find((item) => item.service === this.activeService);
                    if (!existingService) {
                        this.selectedServices.push({
                            service: this.activeService,
                            products: [product],
                            subService: product.child_cat_id,
                        });
                        // console.log(this.selectedServices);
                    } else {
                        const existingProduct = existingService.products.find((item) => item.id === product.id);
                        if (!existingProduct) {
                            existingService.products.push(product);
                            existingService.subService = product.child_cat_id;
                            // console.log('product:'+ existingProduct);
                        }
                    }

                    this.estimatedPrice = this.selectedServices.reduce((sum, service) => {
                        return sum + service.products.reduce((total, priceItem) => {
                            return total + (priceItem.quantity > 0 ? priceItem.quantity * priceItem.product_price : 0);
                        }, 0);
                    }, 0);
                },


                decrementCount(product) {
                    if (product.quantity > 0) {
                        product.quantity--;
                    }

                    const service = this.selectedServices.find(service => service.products.includes(product));
                    if (service && service.products.every(item => item.quantity === 0)) {
                        const index = this.selectedServices.indexOf(service);
                        if (index !== -1) {
                            this.selectedServices.splice(index, 1);
                        }
                    }

                    this.estimatedPrice = this.selectedServices.reduce((sum, service) => {
                        return sum + service.products.reduce((total, priceItem) => {
                            return total + (priceItem.quantity > 0 ? priceItem.quantity * priceItem.product_price : 0);
                        }, 0);
                    }, 0);
                },
                getData: function () {
                    this.isLoading = true;
                    axios.get('/api/services')
                        .then((res) => {
                            this.isLoading = false;
                            console.log(res.data);
                            this.services = res.data.services;
                            const urlParams = new URLSearchParams(window.location.search);
                            this.activeService = urlParams.get("service");
                            this.activeSubCategory = this.getServiceBySlug(this.activeService).subCategories[0].slug;
                            this.activeServiceDetails = this.getServiceBySlug(this.activeService).serviceDetails;
                            this.serviceQueAns = this.getServiceBySlug(this.activeService).serviceQueAns;
                        })
                        .catch((error) => {
                            this.isLoading = false;
                            console.log(error);
                        })
                        .finally(function () {
                            // always executed
                        });
                    // console.log('yes i am working');
                },

                redirectToOtherView() {
                    if(this.selectedServices.length > 0){
                        // Create a new array from this.selectedServices with the modified structure.
                        // Create a new array from this.selectedServices with the modified structure.
                        const modifiedSelectedServices = this.selectedServices.map((serviceItem) => {
                            // Find the corresponding service object to access more data, including the ID.
                            const selectedService = this.services.find(service => service.slug === serviceItem.service);

                            // Check if the corresponding service was found to avoid potential errors.
                            if (!selectedService) {
                                console.error(`Service with slug ${serviceItem.service} not found.`);
                                return null; // or handle this case as appropriate for your application.
                            }

                            // Construct a new object with the desired structure.
                            return {
                                cat: selectedService.id, // Here we use the actual service ID instead of the slug.
                                sub_cat: serviceItem.subService, // 'subService' is renamed to 'sub_cat'.
                                // 'products' is not included in the new object.
                            };
                        });

// Remove any null items that may have been added due to services not found (if any).
                        const cleanedSelectedServices = modifiedSelectedServices.filter(item => item !== null);

                        const selectedServices = encodeURIComponent(JSON.stringify(cleanedSelectedServices));
                    //     const selectedSlugs = this.selectedServices.map(service => service.service);
                    //
                    //     // Initialize selectedServiceIds as an empty array
                    //     let selectedServiceIds = [];
                    //
                    //     // Loop through the services and push matched service IDs into selectedServiceIds
                    //     this.services.forEach(service => {
                    //         if (selectedSlugs.includes(service.slug)) {
                    //             selectedServiceIds.push({ cat: service.id });  // Assuming the id property exists in each service object
                    //         }
                    //
                    //     });
                        const servicesData = encodeURIComponent(JSON.stringify(this.services));
                    const url = "{{ route('booking.details') }}" + "?selectedServices=" + selectedServices + "&services=" + servicesData;
                    window.location.href = url;
                }
                }
            },
        })
        app.mount('#app')
    </script>
@endsection

@extends('layouts.app',['title' => 'Price', 'active' => 'prices'])

@section('content')
    @php
        $sub_active = request()->segment(2);
    @endphp
    <div id="app">
        <div v-if="isLoading" class="loader"><i class="fa fa-spin fa-spinner"></i></div>
        <section class="section-categories bg-boll">
            <div class="container">
                <div class="row">
                    <ul class="filter-by-category d-flex justify-content-center mb-0 pt-16 pb-16">
                        <li v-for="(item,i) in services" :key="i">
                            <a @click.prevent="switchService(item.slug)" href="{{ route('pricing.details', ['service' => ''])}}@{{ item.title }}"
                               :class="{active: item.slug == activeService}">@{{item.title}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- section our prices  -->
        <section v-if="!isLoading" class="section-category-banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="banner-content d-md-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img :src="getActiveServiceBySlug(activeService).icon" :alt="getActiveServiceBySlug(activeService).title">
                                {{--                                <i class="icon icon-wash"></i>--}}
                            </div>
                            <div class="flex-grow-1 ps-md-4">
                                <h1 class="mb-md-3 mb-2 fw-bolder">@{{ getActiveServiceBySlug(activeService).title }}</h1>
                                <p class="mb-md-3 mb-2">@{{ getActiveServiceBySlug(activeService).description }}</p>
                                <p class="color-primary fw-lighter">
                                    @{{ getActiveServiceBySlug(activeService).tagsString }}
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
                                    data-bs-target="#PricelistTab"
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
                            <ul class="services-sub-menu">
                                <li v-for="(item, i) in getActiveServiceBySlug(activeService).itemType">
                                    <a :href="'#' + item.id">@{{ item.name }}</a>
                                </li>
                            </ul>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="tab-content" id="tabContent">
                                        <div class="tab-pane fade show active" id="PricelistTab" role="tabpanel" aria-labelledby="PriceList-tab">
                                            <div class="table">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total price</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(item, i) in getActiveServiceBySlug(activeService).price_details">
                                                        <td>@{{item.heading}}</td>
                                                        <td>@{{getActiveServiceBySlug(activeService).priceSymbol + item.price}}</td>
                                                        <td>
                                                            <div class="counter">
                                                                <div @click.prevent="decrementCount(activeService,i)" class="decrement-count">
                                                                    <i class="fas fa-minus"></i>
                                                                </div>
                                                                <div class="total-count">@{{ item.quantity }}</div>
                                                                <div @click.prevent="incrementCount(activeService,i)" class="increment-count">
                                                                    <i class="fal fa-plus"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>@{{getActiveServiceBySlug(activeService).priceSymbol + item.total_price }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="serviceDetailsTab" role="tabpanel" aria-labelledby="serviceDetails-tab">
                                            <div class="services-details">
                                                <div class="border-bottom d-flex">
                                                    <i class="icon icon-search-service"></i>
                                                    <div class="ms-2">
                                                        <h6>Service Overview</h6>
                                                        <p v-html="getActiveServiceBySlug(activeService).service_overview"></p>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4">
                                                    <i class="icon icon-suitable"></i>
                                                    <div class="ms-2">
                                                        <h6>Suitable for</h6>
                                                        <div v-html="getActiveServiceBySlug(activeService).service_suitable"></div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4">
                                                    <i class="icon icon-dont-include"></i>
                                                    <div class="ms-2">
                                                        <h6>Don’t Include</h6>
                                                        <div v-html="getActiveServiceBySlug(activeService).service_not_include"></div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom d-flex mt-4">
                                                    <i class="icon icon-collection"></i>
                                                    <div class="ms-2">
                                                        <h6>Prepare for Collection</h6>
                                                        <div v-html="getActiveServiceBySlug(activeService).service_collection"></div>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-4">
                                                    <i class="icon icon-laundry-delivery"></i>
                                                    <div class="ms-2">
                                                        <h6>Laundry Delivery</h6>
                                                        <div v-html="getActiveServiceBySlug(activeService).service_delivery"></div>
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
                                            <p>Do I need to list every item? <a href="#" class="color-brand">Learn more</a></p>
                                        </div>
                                        <div v-for="(item,i) in selectedServices" class="services-list border-bottom">
                                            <div class="d-flex">
                                                <img :src="item.icon" :alt="item.title">
                                                <h3>@{{ item.title }}</h3>
                                            </div>
                                            <div v-for="(item, j) in getActiveServiceBySlug(activeService).price_details">
                                                <div class="d-flex justify-content-between" v-if="item.quantity > 0">
                                                    <p>@{{ item.heading }}</p>
                                                    <p>@{{getActiveServiceBySlug(activeService).priceSymbol + item.total_price }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="border-bottom pb-3 pt-3">This is an estimated price, final price
                                            will be calculated after laundry job is done.</p>
                                        <div class="estimated-price d-flex justify-content-between pt-3 pb-3 border-bottom">
                                            <p>Estimated price</p>
                                            <p>@{{ getActiveServiceBySlug(activeService).priceSymbol + estimatedPrice }}</p>
                                        </div>
                                        <div class="text-center mt-40">
                                            <a class="btn btn-brand  py-3 px-32 d-inline-block" href="#">Schedule an
                                                Order</a>
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
        <section v-if="getActiveServiceBySlug(activeService).service_question" class="section section-prices">
            <div class="container text-center">
                <h2 class="mb-32 text-center fw-600">@{{ getActiveServiceBySlug(activeService).service_question }}</h2>
                <h3 class="fw-500">@{{ getActiveServiceBySlug(activeService).service_answer }}</h3>
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
                    services: [
                        {
                            slug: "wash",
                            title: "Wash",
                            icon: "images/icons/trRO9sLvA1bXoKEZ6J8uGgGe3zz7kvQaPOxDAUNF.svg",
                            service_question: "Not sure how much you have?",
                            service_answer: "One load of 6kg is about: 7 shirts + 3 trousers + 7 underwear + 7 pairs of socks"
                        },
                    ],
                    selectedServices: []
                }
            },
            mounted: function () {
                this.getData();
            },
            methods: {
                switchService(slug) {
                    this.activeService = slug;
                },
                getActiveServiceBySlug(slug) {
                    return this.services.filter(function (item) {
                        return item.slug === slug;
                    })[0];
                },
                getServiceTagsBySlug(slug) {
                    let tags = this.services.filter(function (item) {
                        return item.slug === slug;
                    })[0].tags;
                    let tagsArr = [];
                    tags.every(function (item) {
                        tagsArr.push(item.title);
                    });
                    return tagsArr.join(' + ');
                },
                incrementCount(slug, i) {
                    let activeService = this.getActiveServiceBySlug(slug);
                    let prices = activeService.price_details;
                    prices[i].quantity++;
                    prices[i].total_price = prices[i].quantity * parseInt(prices[i].price);
                    this.estimatedPrice += prices[i].total_price;
                    let isServiceAvailable = this.selectedServices.filter(function (item) {
                        console.log(item);
                        return item.key === slug;
                    });
                    console.log(isServiceAvailable);
                    if(isServiceAvailable.length === 0){
                        this.selectedServices.push({
                            [slug]:{
                                title: activeService.title,
                                icon: activeService.icon,
                            }
                        });
                    }
                },
                decrementCount(slug, i) {
                    let prices = this.getActiveServiceBySlug(slug).price_details;
                    if (prices[i].quantity > 0) {
                        prices[i].quantity--;
                        prices[i].total_price = prices[i].quantity * parseInt(prices[i].price);
                    }
                    this.estimatedPrice += prices[i].total_price;
                },
                getData: function () {
                    this.isLoading = true;
                    axios.get('https://dev.hellolaundry.co.uk/api/services')
                        .then((res) => {
                            this.isLoading = false;
                            this.services = res.data.services;
                            console.log(res.data.services);
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
            },
        })
        app.mount('#app')
    </script>
@endsection

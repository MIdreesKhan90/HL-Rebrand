@extends('layouts.app',['title' => 'Booking', 'active' => 'bookings'])
@section('content')
    <div id="app">
        <service-component :services="{{ json_encode($services) }}"></service-component>
        <div v-if="isLoading" class="loader"><i class="fa fa-spin fa-spinner"></i></div>
        <div v-if="currentStep === 1">
            <section class="grey-mid-sec bg-grey" v-if="!isManualAddress">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-lg-8">
                            <div class="back-arrow">
                                <a href="{{route('pricing')}}" title="back" class="arrow-back-link">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>
                            <div class="progress-bar-sec">
                                <h5>Step 1of 6: Find your address</h5>
                                <div class="progress-bar-main bg-dark-grey">
                                    <div class="progress-bar-fill" style="width: 20%;"></div>
                                </div>
                                <p>Next: Collection time</p>
                            </div>
                            <div class="mid-white-box bg-white">
                                <div class="mid-white-box-title">
                                    <h3>Find your address</h3>
                                </div>
                                <form class="address-form">
                                    <div class="form-row">
                                        <div class="form-col form-postcode-col-left">
                                            <div class="inner-form-col">
                                                <label>Enter UK Passcode</label>
                                                <input type="text" v-model="postData.postcode" placeholder="UB3 4FF"
                                                       class="form-control text-uppercase"/>
                                            </div>
                                            <div class="manually-add-list" v-if="addresses">
                                                <a type="button" @click.prevent="isManualAddress = true"
                                                   title="Enter address manually" class="manually-add-list-link">
                                                    Enter address manually
                                                </a>
                                            </div>
                                        </div>
                                        <div class="form-col form-postcode-btn-col">
                                            <button type="submit"
                                                    class="btn btn-brand outline"
                                                    @click.prevent="findAddress()"
                                            >
                                                Find Address
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-row" v-if="addresses">
                                        <div class="form-col form-postcode-col-left">
                                            <div class="inner-form-col">
                                                <label>Select an Address</label>
                                                <select class="form-control" v-model="postData.address">
                                                    <option value="">Please select address</option>
                                                    <option :value="item.formatted_address.join(',')" v-for="(item, i) in addresses" :key="i">
                                                        @{{ item.formatted_address.filter(str => str.trim() !== '').join(', ') + ', ' + postData.postcode }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p v-if="addressMessage" class="mt-2 text-secondary"><small>@{{ addressMessage }}</small></p>
                                </form>
                            </div>
                            <div class="next-btn-main">
                                <div class="next-btn-col">
                                    <a title="Next" @click="nextStep" class="btn btn-brand d-inline-block"
                                       v-if="isButtonDisabled">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="grey-mid-sec bg-grey" v-if="isManualAddress">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-lg-8">
                            <div class="back-arrow">
                                <a href=""
                                   title="back"
                                   @click.prevent="isManualAddress = false"
                                   class="arrow-back-link cursor-pointer">
                                    <i class="fa-solid fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="progress-bar-sec">
                                <h5>Step 1 of 6: Find your address</h5>
                                <div class="progress-bar-main bg-dark-grey">
                                    <div class="progress-bar-fill" style="width: 20%;"></div>
                                </div>
                                <p>Next: Collection time</p>
                            </div>
                            <div class="mid-white-box bg-white mb-15 manual-add-block">
                                <div class="mid-white-box-title">
                                    <h3>Find your address</h3>
                                </div>
                                <form class="address-form">
                                    <div class="form-row">
                                        <div class="form-col form-col-1">
                                            <label>Address Line 1</label>
                                            <input type="text" class="form-control"
                                                   v-model="addressDetails.firstLine"
                                                   placeholder="Flat 63, 62 Example Road"/>
                                        </div>
                                        <div class="form-col form-col-1">
                                            <label>Address Line 2</label>
                                            <input type="text" class="form-control"
                                                   v-model="addressDetails.secondLine"/>
                                        </div>
                                        <div class="form-col form-col-2">
                                            <label>City/Town</label>
                                            <input type="text"
                                                   class="form-control"
                                                   v-model="postData.city"
                                                   placeholder="London"
                                                   readonly
                                            />
                                        </div>
                                        <div class="form-col form-col-2">
                                            <label>Postcode</label>
                                            <input type="text"
                                                   class="form-control"
                                                   v-model="postData.postcode"
                                                   placeholder="QA1 5TF"
                                                   readonly
                                            />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="mid-white-box bg-white address-type-box">
                                <form class="address-form">
                                    <div class="form-row">
                                        <div class="form-col form-col-1">
                                            <label>Please select address type</label>
                                            <div class="address-radio-list">
                                                <ul>
                                                    <li>
                                                        <input type="radio" id="addressType1" name="radio-group" value="1" v-model="postData.type" checked>
                                                        <label for="addressType1"><i class="fa-solid fa-house"></i>Home</label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="addressType2" name="radio-group" value="2" v-model="postData.type">
                                                        <label for="addressType2"><i class="fa-solid fa-briefcase"></i>Office</label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="addressType3" name="radio-group" value="3" v-model="postData.type">
                                                        <label for="addressType3"><i class="fa-solid fa-hotel"></i>Hotel</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="next-btn-main">
                                <div class="next-btn-col">
                                    <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <section class="grey-mid-sec bg-grey" v-if="currentStep === 2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-lg-8">
                        <div class="back-arrow">
                            <a href="" @click.prevent="prevStep" title="back" class="arrow-back-link"><i
                                    class="fa-solid fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="progress-bar-sec">
                            <h5>Step 2 of 6: Collection time</h5>
                            <div class="progress-bar-main bg-dark-grey">
                                <div class="progress-bar-fill" style="width: 40%;"></div>
                            </div>
                            <p>Next: Select service</p>
                        </div>
                        <div class="mid-white-box bg-white manual-add-block">
                            <div class="mid-white-box-title">
                                <h3>Collection time</h3>
                            </div>
                            <form class="address-form">
                                <div class="form-row">
                                    <div class="form-col form-col-2">
                                        <label>Select day</label>
                                        <select @change="getUpdatedDateTime('pd')" class="form-control" v-model="postData.pickup_date">
                                            <option value="">Select preferable day</option>
                                            <option v-for="(value, key) in dateTimeSlot.pickUpDates" :value="value" :key="key">
                                                @{{key}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-col form-col-2">
                                        <label>Select time</label>
                                        <select @change="getUpdatedDateTime('pt')" class="form-control" v-model="postData.pickup_time">
                                            <option value="">Select preferable time</option>
                                            <option v-for="(item, i) in dateTimeSlot.pickUpTimeSlots" :value="item" :key="i">
                                                @{{ item }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-col form-col-1">
                                        <label>Driver instructions</label>
                                        <select class="form-control" v-model="postData.collection_instructions">
                                            <option value="Collect from me in person">Collect from me in person</option>
                                            <option value="Collect from outside">Collect from outside</option>
                                            <option value="Collect from reception/porter">Collect from reception/porter</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mid-white-box bg-white manual-add-block">
                            <div class="mid-white-box-title">
                                <h3>Delivery time</h3>
                            </div>
                            <form class="address-form">
                                <div class="form-row">
                                    <div class="form-col form-col-2">
                                        <label>Select day</label>
                                        <select @change="getUpdatedDateTime('dd')" class="form-control" v-model="postData.delivery_date">
                                            <option value="">Select preferable day</option>
                                            <option v-for="(value, key) in dateTimeSlot.deliveryDates" :value="value" :key="key">
                                                @{{ key }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-col form-col-2">
                                        <label>Select time</label>
                                        <select class="form-control" v-model="postData.delivery_time">
                                            <option value="">Select preferable time</option>
                                            <option v-for="(item, i) in dateTimeSlot.deliveryTimeSlots" :value="item" :key="i">
                                                @{{ item }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-col form-col-1">
                                        <label>Driver instructions</label>
                                        <select class="form-control" v-model="postData.delivery_instructions">
                                            <option value="Deliver to me in person">Deliver to me in person</option>
                                            <option value="Leave at the door">Leave at the door</option>
                                            <option value="Deliver to the reception/porter">Deliver to the reception/porter</option>
                                        </select>
                                    </div>
                                    <div class="form-col form-col-1">
                                        <label>More details (optional)</label>
                                        <textarea class="form-control" v-model="postData.other_requests"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--                        <div class="mid-white-box bg-white manual-add-block">--}}
                        {{--                            <div class="mid-white-box-title">--}}
                        {{--                                <h3>Frequency</h3>--}}
                        {{--                            </div>--}}
                        {{--                            <form class="address-form">--}}
                        {{--                                <div class="form-row">--}}
                        {{--                                    <div class="form-col form-col-1 custom-radio-list">--}}
                        {{--                                        <label>How example exmapke</label>--}}
                        {{--                                        <div class="form-row">--}}
                        {{--                                            <div class="form-col form-col-2">--}}
                        {{--                                                <input type="radio" id="radio1" name="radio-group" checked>--}}
                        {{--                                                <label for="radio1">Just once</label>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="form-col form-col-2">--}}
                        {{--                                                <input type="radio" id="radio2" name="radio-group">--}}
                        {{--                                                <label for="radio2">Weekly</label>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="form-col form-col-2">--}}
                        {{--                                                <input type="radio" id="radio3" name="radio-group">--}}
                        {{--                                                <label for="radio3">Every Two Weeks</label>--}}
                        {{--                                            </div>--}}
                        {{--                                            <div class="form-col form-col-2">--}}
                        {{--                                                <input type="radio" id="radio4" name="radio-group">--}}
                        {{--                                                <label for="radio4">Every Four Weeks</label>--}}
                        {{--                                            </div>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </form>--}}
                        {{--                        </div>--}}
                        <div class="next-btn-main">
                            <div class="next-btn-col">
                                <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="grey-mid-sec bg-grey" v-if="currentStep === 3">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-lg-8">
                        <div class="back-arrow">
                            <a href=""
                               @click.prevent="prevStep"
                               title="back"
                               class="arrow-back-link">
                                <i class="fa-solid fa-arrow-left"></i>
                                Back
                            </a>
                        </div>
                        <div class="progress-bar-sec">
                            <h5>Step 3 of 6: Select service</h5>
                            <div class="progress-bar-main bg-dark-grey">
                                <div class="progress-bar-fill" style="width: 80%;"></div>
                            </div>
                            <p>Next: Contact details</p>
                        </div>

                        <div class="mid-white-box bg-white mb-15 manual-add-block">
                            <div class="mid-white-box-title">
                                <h3>Select service</h3>
                            </div>
                            <div class="service-list">
                                <div class="service-item" v-for="(item, i) in postData.services" :key="i">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="list-details d-md-flex align-items-center">
                                                <div class="flex-shrink-0"><img class="w-75" :src="item.icon" alt="Icon"></div>
                                                <div class="flex-grow-1 ps-md-3 service-item-content">
                                                    <h3 class="mb-md-2 mb-2">@{{item.title}}</h3>
                                                    <p>@{{ item.tagsString }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="list-details d-md-flex align-items-center justify-content-end">
                                                <div class="see-details-txt">
                                                    <a href="#" title="See details">See details</a>
                                                </div>
                                                <div class="price-btn-col">
                                                    <!-- Check if item.childCategories has data -->
                                                    <a v-if="item.childCategories && item.childCategories.length > 0" @click.prevent="isServiceSelected(item.id) ? toggleService(item.id) : openServiceModal()" class="btn btn-brand py-3 px-32 ms-md-4" :class="{outline: !isServiceSelected(item.id)}" href="#">
                                                        @{{ isServiceSelected(item.id) ? 'Added' : '+ Add'  }}
                                                    </a>
                                                    <!-- If item.childCategories doesn't have data -->
                                                    <a v-else @click.prevent="toggleService(item.id,null,item.title)" class="btn btn-brand py-3 px-32 ms-md-4" :class="{outline: !isServiceSelected(item.id)}" href="#">
                                                        @{{ isServiceSelected(item.id) ? 'Added' : '+ Add'  }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="next-btn-main">
                            <div class="next-btn-col">
                                <button :disabled="!postData.selectedServices.length" @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="grey-mid-sec bg-grey" v-if="currentStep === 4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-lg-8">
                        <div class="back-arrow">
                            <a href=""
                               @click.prevent="prevStep"
                               title="back" class="arrow-back-link">
                                <i class="fa-solid btn-brand fa-arrow-left"></i>
                                Back
                            </a>
                        </div>
                        <div class="progress-bar-sec">
                            <h5>Step 4 of 6: Contact details</h5>
                            <div class="progress-bar-main bg-dark-grey">
                                <div class="progress-bar-fill" style="width: 60%;"></div>
                            </div>
                            <p>Next: Summary</p>
                            <div v-show="!isCheckUserLoggedIn" class="alert alert-primary d-flex justify-content-between" role="alert">
                                <div>
                                    <p class="text-primary mb-2" style="font-weight: bold">Already have an account?</p>
                                    <a href="" @click.prevent="showLoginModal"
                                       class="text-decoration-none font-bold"
                                       style="font-size: large; font-weight: bold">Log
                                        in now</a>
                                </div>
                                <div class="mt-1">
                                    <p><i class="fa-solid fa-circle-question" style="font-size: 45px;"></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="mid-white-box bg-white manual-add-block">
                            <div class="mid-white-box-title">
                                <h3>Contact details</h3>
                            </div>
                            <form class="address-form">
                                <div class="form-row">
                                    <div class="form-col form-col-1 custom-radio-list">
                                        <label>Want to receive order updates? Provide following details</label>
                                        <div class="form-row">
                                            <div class="form-col form-col-2">
                                                <input type="radio" id="customerType1" v-model="postData.customer_type" value="1" name="customerType" checked>
                                                <label for="customerType1">Individual</label>
                                            </div>
                                            <div class="form-col form-col-2">
                                                <input type="radio" id="customerType2" v-model="postData.customer_type" value="2" name="customerType">
                                                <label for="customerType2">Company</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-col form-col-2">
                                        <label>First name</label>
                                        <input type="text" placeholder="First name" v-model="postData.uFName"
                                               class="form-control" required :readonly="isCheckUserLoggedIn"/>
                                        <span class="error-message" v-if="formErrors.uFName">@{{ formErrors.uFName }}</span>
                                    </div>
                                    <div class="form-col form-col-2">
                                        <label>Last name</label>
                                        <input type="text" placeholder="Last name" v-model="postData.uLName"
                                               class="form-control" required :readonly="isCheckUserLoggedIn"/>
                                        <span class="error-message" v-if="formErrors.uLName">@{{ formErrors.uLName }}</span>
                                    </div>
                                    <template v-if="postData.customer_type == 2">
                                        <div class="form-col form-col-2">
                                            <label>Company name</label>
                                            <input type="text"
                                                   placeholder="Company name"
                                                   v-model="postData.uCompanyName"
                                                   class="form-control"
                                                   :readonly="isCheckUserLoggedIn"
                                                   required
                                            />
                                            <span class="error-message" v-if="formErrors.uCompanyName">@{{ formErrors.uCompanyName }}</span>
                                        </div>
                                        <div class="form-col form-col-2">
                                            <label>Tax number</label>
                                            <input type="text"
                                                   placeholder="Tax number"
                                                   v-model="postData.taxNumber"
                                                   class="form-control"
                                                   :readonly="isCheckUserLoggedIn"
                                                   required
                                            />
                                            <span class="error-message" v-if="formErrors.taxNumber">@{{ formErrors.taxNumber }}</span>
                                        </div>
                                    </template>

                                    <div class="form-col form-col-2">
                                        <label>Email address</label>
                                        <input type="email" @input="validateEmail" placeholder="example@gmail.com"
                                               v-model="postData.email"
                                               class="form-control" required :readonly="isCheckUserLoggedIn"/>
{{--                                        <span v-if="!validEmail && postData.email !== ''" class="error-message">Invalid Email</span>--}}
                                        <span class="error-message" v-if="formErrors.email">@{{ formErrors.email }}</span>
                                        <p v-if="emailCheckMessage" class="mt-2 text-secondary"><small
                                                class="error-message">@{{ emailCheckMessage
                                                }}</small><a href="" style="color: red;"
                                                             @click.prevent="showLoginModal">Login</a></p>
                                    </div>
                                    <div class="form-col form-col-2">
                                        <label>Mobile number</label>
                                        <div class="d-flex">
                                            <div class="w-25 input-phone-with-code">
                                                <input class="form-control input-phone-code" v-model="postData.phone_code"/>
                                                <select
                                                    id="phoneCode"
                                                    name="phoneCode"
                                                    v-model="postData.phone_code"
                                                    class="form-control input-phone-number"
                                                >
                                                    <option hidden="hidden">Code</option>
                                                    <option value="+93"> Afghanistan +93 </option><option value="+358"> Aland Islands +358 </option><option value="+355"> Albania +355 </option><option value="+213"> Algeria +213 </option><option value="+1684"> AmericanSamoa +1684 </option><option value="+376"> Andorra +376 </option><option value="+244"> Angola +244 </option><option value="+1264"> Anguilla +1264 </option><option value="+672"> Antarctica +672 </option><option value="+1268"> Antigua and Barbuda +1268 </option><option value="+54"> Argentina +54 </option><option value="+374"> Armenia +374 </option><option value="+297"> Aruba +297 </option><option value="+247"> Ascension Island +247 </option><option value="+61"> Australia +61 </option><option value="+43"> Austria +43 </option><option value="+994"> Azerbaijan +994 </option><option value="+1242"> Bahamas +1242 </option><option value="+973"> Bahrain +973 </option><option value="+880"> Bangladesh +880 </option><option value="+1246"> Barbados +1246 </option><option value="+375"> Belarus +375 </option><option value="+32"> Belgium +32 </option><option value="+501"> Belize +501 </option><option value="+229"> Benin +229 </option><option value="+1441"> Bermuda +1441 </option><option value="+975"> Bhutan +975 </option><option value="+591"> Bolivia +591 </option><option value="+387"> Bosnia and Herzegovina +387 </option><option value="+267"> Botswana +267 </option><option value="+55"> Brazil +55 </option><option value="+246"> British Indian Ocean Territory +246 </option><option value="+673"> Brunei Darussalam +673 </option><option value="+359"> Bulgaria +359 </option><option value="+226"> Burkina Faso +226 </option><option value="+257"> Burundi +257 </option><option value="+855"> Cambodia +855 </option><option value="+237"> Cameroon +237 </option><option value="+1"> Canada +1 </option><option value="+238"> Cape Verde +238 </option><option value="+1345"> Cayman Islands +1345 </option><option value="+236"> Central African Republic +236 </option><option value="+235"> Chad +235 </option><option value="+56"> Chile +56 </option><option value="+86"> China +86 </option><option value="+61"> Christmas Island +61 </option><option value="+61"> Cocos (Keeling) Islands +61 </option><option value="+57"> Colombia +57 </option><option value="+269"> Comoros +269 </option><option value="+242"> Congo +242 </option><option value="+682"> Cook Islands +682 </option><option value="+506"> Costa Rica +506 </option><option value="+385"> Croatia +385 </option><option value="+53"> Cuba +53 </option><option value="+357"> Cyprus +357 </option><option value="+420"> Czech Republic +420 </option><option value="+243"> Democratic Republic of the Congo +243 </option><option value="+45"> Denmark +45 </option><option value="+253"> Djibouti +253 </option><option value="+1767"> Dominica +1767 </option><option value="+1849"> Dominican Republic +1849 </option><option value="+593"> Ecuador +593 </option><option value="+20"> Egypt +20 </option><option value="+503"> El Salvador +503 </option><option value="+240"> Equatorial Guinea +240 </option><option value="+291"> Eritrea +291 </option><option value="+372"> Estonia +372 </option><option value="+268"> Eswatini +268 </option><option value="+251"> Ethiopia +251 </option><option value="+500"> Falkland Islands (Malvinas) +500 </option><option value="+298"> Faroe Islands +298 </option><option value="+679"> Fiji +679 </option><option value="+358"> Finland +358 </option><option value="+33"> France +33 </option><option value="+594"> French Guiana +594 </option><option value="+689"> French Polynesia +689 </option><option value="+241"> Gabon +241 </option><option value="+220"> Gambia +220 </option><option value="+995"> Georgia +995 </option><option value="+49"> Germany +49 </option><option value="+233"> Ghana +233 </option><option value="+350"> Gibraltar +350 </option><option value="+30"> Greece +30 </option><option value="+299"> Greenland +299 </option><option value="+1473"> Grenada +1473 </option><option value="+590"> Guadeloupe +590 </option><option value="+1671"> Guam +1671 </option><option value="+502"> Guatemala +502 </option><option value="+44"> Guernsey +44 </option><option value="+224"> Guinea +224 </option><option value="+245"> Guinea-Bissau +245 </option><option value="+592"> Guyana +592 </option><option value="+509"> Haiti +509 </option><option value="+379"> Holy See (Vatican City State) +379 </option><option value="+504"> Honduras +504 </option><option value="+852"> Hong Kong +852 </option><option value="+36"> Hungary +36 </option><option value="+354"> Iceland +354 </option><option value="+91"> India +91 </option><option value="+62"> Indonesia +62 </option><option value="+98"> Iran +98 </option><option value="+964"> Iraq +964 </option><option value="+353"> Ireland +353 </option><option value="+44"> Isle of Man +44 </option><option value="+972"> Israel +972 </option><option value="+39"> Italy +39 </option><option value="+225"> Ivory Coast / Cote d'Ivoire +225 </option><option value="+1876"> Jamaica +1876 </option><option value="+81"> Japan +81 </option><option value="+44"> Jersey +44 </option><option value="+962"> Jordan +962 </option><option value="+77"> Kazakhstan +77 </option><option value="+254"> Kenya +254 </option><option value="+686"> Kiribati +686 </option><option value="+850"> Korea, Democratic People's Republic of Korea +850 </option><option value="+82"> Korea, Republic of South Korea +82 </option><option value="+383"> Kosovo +383 </option><option value="+965"> Kuwait +965 </option><option value="+996"> Kyrgyzstan +996 </option><option value="+856"> Laos +856 </option><option value="+371"> Latvia +371 </option><option value="+961"> Lebanon +961 </option><option value="+266"> Lesotho +266 </option><option value="+231"> Liberia +231 </option><option value="+218"> Libya +218 </option><option value="+423"> Liechtenstein +423 </option><option value="+370"> Lithuania +370 </option><option value="+352"> Luxembourg +352 </option><option value="+853"> Macau +853 </option><option value="+261"> Madagascar +261 </option><option value="+265"> Malawi +265 </option><option value="+60"> Malaysia +60 </option><option value="+960"> Maldives +960 </option><option value="+223"> Mali +223 </option><option value="+356"> Malta +356 </option><option value="+692"> Marshall Islands +692 </option><option value="+596"> Martinique +596 </option><option value="+222"> Mauritania +222 </option><option value="+230"> Mauritius +230 </option><option value="+262"> Mayotte +262 </option><option value="+52"> Mexico +52 </option><option value="+691"> Micronesia, Federated States of Micronesia +691 </option><option value="+373"> Moldova +373 </option><option value="+377"> Monaco +377 </option><option value="+976"> Mongolia +976 </option><option value="+382"> Montenegro +382 </option><option value="+1664"> Montserrat +1664 </option><option value="+212"> Morocco +212 </option><option value="+258"> Mozambique +258 </option><option value="+95"> Myanmar +95 </option><option value="+264"> Namibia +264 </option><option value="+674"> Nauru +674 </option><option value="+977"> Nepal +977 </option><option value="+31"> Netherlands +31 </option><option value="+599"> Netherlands Antilles +599 </option><option value="+687"> New Caledonia +687 </option><option value="+64"> New Zealand +64 </option><option value="+505"> Nicaragua +505 </option><option value="+227"> Niger +227 </option><option value="+234"> Nigeria +234 </option><option value="+683"> Niue +683 </option><option value="+672"> Norfolk Island +672 </option><option value="+1670"> Northern Mariana Islands +1670 </option><option value="+389"> North Macedonia +389 </option><option value="+47"> Norway +47 </option><option value="+968"> Oman +968 </option><option value="+92"> Pakistan +92 </option><option value="+680"> Palau +680 </option><option value="+970"> Palestine +970 </option><option value="+507"> Panama +507 </option><option value="+675"> Papua New Guinea +675 </option><option value="+595"> Paraguay +595 </option><option value="+51"> Peru +51 </option><option value="+63"> Philippines +63 </option><option value="+872"> Pitcairn +872 </option><option value="+48"> Poland +48 </option><option value="+351"> Portugal +351 </option><option value="+1939"> Puerto Rico +1939 </option><option value="+974"> Qatar +974 </option><option value="+262"> Reunion +262 </option><option value="+40"> Romania +40 </option><option value="+7"> Russia +7 </option><option value="+250"> Rwanda +250 </option><option value="+590"> Saint Barthelemy +590 </option><option value="+290"> Saint Helena, Ascension and Tristan Da Cunha +290 </option><option value="+1869"> Saint Kitts and Nevis +1869 </option><option value="+1758"> Saint Lucia +1758 </option><option value="+590"> Saint Martin +590 </option><option value="+508"> Saint Pierre and Miquelon +508 </option><option value="+1784"> Saint Vincent and the Grenadines +1784 </option><option value="+685"> Samoa +685 </option><option value="+378"> San Marino +378 </option><option value="+239"> Sao Tome and Principe +239 </option><option value="+966"> Saudi Arabia +966 </option><option value="+221"> Senegal +221 </option><option value="+381"> Serbia +381 </option><option value="+248"> Seychelles +248 </option><option value="+232"> Sierra Leone +232 </option><option value="+65"> Singapore +65 </option><option value="+1721"> Sint Maarten +1721 </option><option value="+421"> Slovakia +421 </option><option value="+386"> Slovenia +386 </option><option value="+677"> Solomon Islands +677 </option><option value="+252"> Somalia +252 </option><option value="+27"> South Africa +27 </option><option value="+500"> South Georgia and the South Sandwich Islands +500 </option><option value="+211"> South Sudan +211 </option><option value="+34"> Spain +34 </option><option value="+94"> Sri Lanka +94 </option><option value="+249"> Sudan +249 </option><option value="+597"> Suriname +597 </option><option value="+47"> Svalbard and Jan Mayen +47 </option><option value="+46"> Sweden +46 </option><option value="+41"> Switzerland +41 </option><option value="+963"> Syrian Arab Republic +963 </option><option value="+886"> Taiwan +886 </option><option value="+992"> Tajikistan +992 </option><option value="+255"> Tanzania, United Republic of Tanzania +255 </option><option value="+66"> Thailand +66 </option><option value="+670"> Timor-Leste +670 </option><option value="+228"> Togo +228 </option><option value="+690"> Tokelau +690 </option><option value="+676"> Tonga +676 </option><option value="+1868"> Trinidad and Tobago +1868 </option><option value="+216"> Tunisia +216 </option><option value="+90"> Turkey +90 </option><option value="+993"> Turkmenistan +993 </option><option value="+1649"> Turks and Caicos Islands +1649 </option><option value="+688"> Tuvalu +688 </option><option value="+256"> Uganda +256 </option><option value="+380"> Ukraine +380 </option><option value="+971"> United Arab Emirates +971 </option><option value="+44"> United Kingdom +44 </option><option value="+1"> United States +1 </option><option value="+598"> Uruguay +598 </option><option value="+998"> Uzbekistan +998 </option><option value="+678"> Vanuatu +678 </option><option value="+58"> Venezuela, Bolivarian Republic of Venezuela +58 </option><option value="+84"> Vietnam +84 </option><option value="+1284"> Virgin Islands, British +1284 </option><option value="+1340"> Virgin Islands, U.S. +1340 </option><option value="+681"> Wallis and Futuna +681 </option><option value="+967"> Yemen +967 </option><option value="+260"> Zambia +260 </option><option value="+263"> Zimbabwe +263 </option></select>
                                            </div>

                                            <input type="tel" v-model="postData.phone_number"
                                                   class="form-control" required />
                                        </div>
                                        <span class="error-message" v-if="formErrors.phone_number">@{{ formErrors.phone_number }}</span>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="next-btn-main">
                            <div class="next-btn-col">
                                <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="grey-mid-sec bg-grey" v-if="currentStep === 5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-lg-8">
                        <div class="back-arrow">
                            <a @click="prevStep" title="back" class="arrow-back-link"><i
                                    class="fa-solid fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="progress-bar-sec">
                            <h5>Step 5 of 6: Summary</h5>
                            <div class="progress-bar-main bg-dark-grey">
                                <div class="progress-bar-fill" style="width: 80%;"></div>
                            </div>
                            <p>Next: Payment details</p>
                        </div>
                        <div class="mid-white-box bg-white manual-add-block">
                            <div class="mid-white-box-title justify-content-between">
                                <h3>Order delivery details</h3>
                                <a type="button" title="Edit" class="edit-link">Edit</a>
                            </div>
                            <div class="summary-content">
                                <h6>Address</h6>
                                <h6>Collection time</h6>
                                <p v-if="isManualAddress">@{{ postData.manual_address + "," + postData.city + "," + postData.country + "," + postData.postcode}}</p>
                                <p v-else>@{{ postData.address + "," + postData.postcode}}</p>
                                <p>@{{ postData.pickup_date + " " + postData.pickup_time }}</p>
                                <h6>Delivery time</h6>
                                <p>@{{ postData.delivery_date + " " + postData.delivery_time }}</p>
                            </div>
                        </div>
                        <div class="mid-white-box bg-white manual-add-block">
                            <div class="mid-white-box-title justify-content-between">
                                <h3>Contact details</h3>
                                <a type="button" title="Edit" class="edit-link">Edit</a>
                            </div>
                            <div class="summary-content">
                                <h6>Full name</h6>
                                <p>@{{ postData.uFName + " " + postData.uLName}}</p>
                                <h6>Email address</h6>
                                <p><a :href="'mailto:'+postData.email" title="mail us">@{{ postData.email }}</a></p>
                                <h6>Mobile number</h6>
                                <p><a title="call us">@{{ postData.phone_number }}</a></p>
                            </div>
                        </div>
                        <div class="next-btn-main">
                            <div class="next-btn-col">
                                <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="grey-mid-sec bg-grey" v-if="currentStep === 6">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-lg-8">
                        <div class="back-arrow">
                            <a href="" @click.prevent="prevStep" title="back" class="arrow-back-link"><i
                                    class="fa-solid fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="progress-bar-sec">
                            <h5>Step 6 of 6:Payment details</h5>

                            <div class="progress-bar-main bg-dark-grey">
                                <div class="progress-bar-fill" style="width: 100%;"></div>
                            </div>
                        </div>

                        <div class="mid-white-box bg-white mb-15 manual-add-block">
                            <div class="mid-white-box-title">
                                <h3>Payment Details</h3>
                            </div>
                            <div class="jumbotron jumbotron-fluid">
                                <div class="container">
                                    <p class="is-justify-content-center mb-3">
                                        <b>Note:</b>
                                        We will authorize your card with a pre-payment of £20. The final value is
                                        calculated after we count / weigh your order. You can calculate the approximate
                                        price using our price list. Our minimum order is £20.
                                    </p>
                                    <hr>
                                    <p class="is-justify-content-center mb-3">By continuing you agree to our <a href="">Terms & Conditions</a> and <a
                                            href="">Privacy Policy</a>. We will authorize your card a pre payment of £20</p>
                                </div>
                            </div>
                        </div>

                        <div class="next-btn-main">
                            <div class="next-btn-col">
                                <a @click="this.checkout()" title="Next" class="btn btn-brand d-inline-block">Check Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{--        <section class="grey-mid-sec bg-grey cart-mid-sec" v-if="currentStep === 4">--}}
        {{--            <div class="container">--}}
        {{--                <div class="row justify-content-center">--}}
        {{--                    <div class="col-sm-12 col-lg-8">--}}
        {{--                        <div class="back-arrow">--}}
        {{--                            <a @click="prevStep" title="back" class="arrow-back-link"><i--}}
        {{--                                    class="fa-solid fa-arrow-left"></i> Back</a>--}}
        {{--                        </div>--}}
        {{--                        <div class="progress-bar-sec">--}}
        {{--                            <h5>Step 4 of 6: Your cart</h5>--}}

        {{--                            <div class="progress-bar-main bg-dark-grey">--}}
        {{--                                <div class="progress-bar-fill" style="width: 70%;"></div>--}}
        {{--                            </div>--}}
        {{--                            <p>Next: Summary</p>--}}
        {{--                        </div>--}}
        {{--                        <div class="mid-white-box bg-white manual-add-block mb-0">--}}
        {{--                            <div class="mid-white-box-title justify-content-between">--}}
        {{--                                <h3>Your cart</h3>--}}
        {{--                                <a type="button" title="Edit" class="edit-link">Edit</a>--}}
        {{--                            </div>--}}
        {{--                            <div class="service-list">--}}
        {{--                                <div v-if="selectedServices.length > 0">--}}
        {{--                                    <div v-for="(service, index) in selectedServices" :key="index" class="service-item">--}}
        {{--                                        <div class="list-details d-md-flex">--}}
        {{--                                            <div class="mt-3">--}}
        {{--                                                <img :src="getServiceIcon(service.service)" class="w-40" :alt="getServiceTitle(service.service)">--}}
        {{--                                            </div>--}}
        {{--                                            <div class="flex-grow-1 ps-md-3 service-item-content">--}}
        {{--                                                <div class="cart-table-col">--}}
        {{--                                                    <h4>@{{ getServiceTitle(service.service) }}</h4>--}}
        {{--                                                    <div class="cart-table">--}}
        {{--                                                        <table>--}}
        {{--                                                            <tbody>--}}
        {{--                                                            <tr v-for="(product, j) in service.products" :key="j">--}}
        {{--                                                                <td class="cart-table-title-col">--}}
        {{--                                                                    <div class="cart-table-title">--}}
        {{--                                                                        @{{ product.product_name }}--}}
        {{--                                                                    </div>--}}
        {{--                                                                </td>--}}
        {{--                                                                <td class="cart-table-counter-col">--}}
        {{--                                                                    <div class="counter">--}}
        {{--                                                                        <div class="decrement-count" @click="decrementQuantity(product)">--}}
        {{--                                                                            <i class="fas fa-minus"></i>--}}
        {{--                                                                        </div>--}}
        {{--                                                                        <div class="total-count">@{{ product.quantity }}</div>--}}
        {{--                                                                        <div class="increment-count" @click="incrementQuantity(product)">--}}
        {{--                                                                            <i class="fal fa-plus"></i>--}}
        {{--                                                                        </div>--}}
        {{--                                                                    </div>--}}
        {{--                                                                </td>--}}
        {{--                                                                <td class="cart-table-price-col">--}}
        {{--                                                                    <div class="cart-table-price">--}}
        {{--                                                                        @{{ getServicePriceSymbol(service.service) + (product.product_price * product.quantity) }}--}}
        {{--                                                                    </div>--}}
        {{--                                                                </td>--}}
        {{--                                                                <td class="cart-table-del-col">--}}
        {{--                                                                    <div class="cart-table-del" @click="removeProduct(service, product)">--}}
        {{--                                                                        <i class="fa-solid fa-trash" title="Remove"></i>--}}
        {{--                                                                    </div>--}}
        {{--                                                                </td>--}}
        {{--                                                            </tr>--}}
        {{--                                                            </tbody>--}}
        {{--                                                        </table>--}}
        {{--                                                    </div>--}}
        {{--                                                </div>--}}
        {{--                                            </div>--}}
        {{--                                        </div>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                                <p class="text-center m-3" v-else>No services selected.</p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="cart-total-price">--}}
        {{--                            <div class="cart-total-row">--}}
        {{--                                <div class="cart-total-title">--}}
        {{--                                    <span>Total</span>--}}
        {{--                                </div>--}}
        {{--                                <div class="cart-total-final">--}}
        {{--                                    <span>£@{{ totalEstimate.toFixed(2) }}</span>--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="next-btn-main">--}}
        {{--                            <div class="next-btn-col">--}}
        {{--                                <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Check out</a>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </section>--}}

        {{--        <section class="grey-mid-sec bg-grey" v-if="currentStep === 6">--}}
        {{--            <div class="container">--}}
        {{--                <div class="row justify-content-center">--}}
        {{--                    <div class="col-sm-12 col-lg-8">--}}
        {{--                        <div class="back-arrow">--}}
        {{--                            <a @click="prevStep" title="back" class="arrow-back-link"><i--}}
        {{--                                    class="fa-solid fa-arrow-left"></i> Back</a>--}}
        {{--                        </div>--}}
        {{--                        <div class="progress-bar-sec">--}}
        {{--                            <h5>Step 6 of 6: Select service</h5>--}}

        {{--                            <div class="progress-bar-main bg-dark-grey">--}}
        {{--                                <div class="progress-bar-fill" style="width: 100%;"></div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="mid-white-box bg-white manual-add-block">--}}
        {{--                            <div class="mid-white-box-title">--}}
        {{--                                <h3>Confirm order and pay</h3>--}}
        {{--                            </div>--}}
        {{--                            <form class="address-form">--}}
        {{--                                <div class="form-row">--}}
        {{--                                    <div class="form-col form-col-1">--}}
        {{--                                        <label>Payment methods</label>--}}
        {{--                                        <div class="pay-list">--}}
        {{--                                            <ul>--}}
        {{--                                                <li>--}}
        {{--                                                    <input type="radio" id="pay1" name="radio-group" checked>--}}
        {{--                                                    <label for="pay1">--}}
        {{--                                                        <img src="assets/images/icons/pay-icn1.svg" alt="icon"/>--}}
        {{--                                                        <i class="fa-solid fa-circle-check"></i>--}}
        {{--                                                    </label>--}}
        {{--                                                </li>--}}
        {{--                                                <li>--}}
        {{--                                                    <input type="radio" id="pay2" name="radio-group">--}}
        {{--                                                    <label for="pay2">--}}
        {{--                                                        <img src="assets/images/icons/pay-icn2.svg" alt="icon"/>--}}
        {{--                                                        <i class="fa-solid fa-circle-check"></i>--}}
        {{--                                                    </label>--}}
        {{--                                                </li>--}}
        {{--                                                <li>--}}
        {{--                                                    <input type="radio" id="pay3" name="radio-group">--}}
        {{--                                                    <label for="pay3">--}}
        {{--                                                        <img src="assets/images/icons/pay-icn3.svg" alt="icon"/>--}}
        {{--                                                        <i class="fa-solid fa-circle-check"></i>--}}
        {{--                                                    </label>--}}
        {{--                                                </li>--}}
        {{--                                                <li>--}}
        {{--                                                    <input type="radio" id="pay4" name="radio-group">--}}
        {{--                                                    <label for="pay4">--}}
        {{--                                                        <img src="assets/images/icons/pay-icn4.svg" alt="icon"/>--}}
        {{--                                                        <i class="fa-solid fa-circle-check"></i>--}}
        {{--                                                    </label>--}}
        {{--                                                </li>--}}
        {{--                                            </ul>--}}
        {{--                                        </div>--}}
        {{--                                    </div>--}}
        {{--                                    <div class="form-col form-col-1">--}}
        {{--                                        <label>Account name</label>--}}
        {{--                                        <input type="text" placeholder="Enter account name" class="form-control"/>--}}
        {{--                                    </div>--}}
        {{--                                    <div class="form-col form-col-1">--}}
        {{--                                        <label>Bank account number</label>--}}
        {{--                                        <input type="text" placeholder="Between 6-10 digits long" class="form-control"/>--}}
        {{--                                    </div>--}}
        {{--                                    <div class="form-col form-col-1">--}}
        {{--                                        <label>Sort code</label>--}}
        {{--                                        <input type="email" placeholder="00-00-00" class="form-control"/>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </form>--}}
        {{--                        </div>--}}
        {{--                        <div class="next-btn-main">--}}
        {{--                            <div class="next-btn-col final-price-btn">--}}
        {{--                                <a @click="nextStep" title="Pay £232.76" class="btn btn-brand d-inline-block">Pay--}}
        {{--                                    £@{{ totalEstimate.toFixed(2) }}</a>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </section>--}}
{{--        <section class="thanks-content bg-light" v-if="currentStep === 6">--}}
{{--            <div class="container">--}}
{{--                <div class="row justify-content-center">--}}
{{--                    <div class="col-md-8 text-center">--}}
{{--                        <div class="thanks-inner bg-white ">--}}
{{--                            <img src="assets/images/thanks-page-img.svg" alt="" class="mb-5 ">--}}
{{--                            <h3 class="mb-4">Awesome!<br>Your laundry has been placed</h3>--}}
{{--                            <p>Hello Laundry rider is on his way to pickup your laundry.</p>--}}
{{--                        </div>--}}
{{--                        <a class="btn btn-brand  py-3 px-45 d-table ml-auto mt-40" href="{{url('/')}}">Continue</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}

        <!-- Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="loginModalLabel">Login</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{--                        <form class="row g-3">--}}
                        {{--                            <div class="col-12">--}}
                        {{--                                <label for="email" class="form-label">Email</label><span class="text-danger">*</span>--}}
                        {{--                                <input type="text" class="form-control" id="email" placeholder="Email"--}}
                        {{--                                       v-model="login.email">--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-md-12">--}}
                        {{--                                <label for="password" class="form-label">Password <span--}}
                        {{--                                        class="text-danger">*</span></label>--}}
                        {{--                                <input type="password" class="form-control" id="password" placeholder="Password"--}}
                        {{--                                       v-model="login.password">--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-12 text-center">--}}
                        {{--                                <button type="button" @click.prevent="loginAccount" class="btn btn-brand">Login</button>--}}
                        {{--                                @if (Route::has('password.request'))--}}
                        {{--                                    <a class="color-brand text-decoration-none fw-bolder mt-3 text-end d-block" href="{{ route('password.request') }}">--}}
                        {{--                                        {{ __('Forgot Your Password?') }}--}}
                        {{--                                    </a>--}}
                        {{--                                @endif--}}
                        {{--                                <div class="text-center mt-32">--}}
                        {{--                                    <a class="btn google-link" href="/login/oauth/google"><i class="icon icon-google me-2"></i> Sign in with Google</a>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </form>--}}
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label mb-2">Email address</label>
                                <input type="hidden" name="return_url" value="booking?step=4">
                                <input type="hidden" name="order_data" :value="postDataJson">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
{{--                            <div class="mb-4">--}}
{{--                                <label for="password" class="form-label">Password</label>--}}
{{--                                <input id="password" type="password"--}}
{{--                                       class="form-control @error('password') is-invalid @enderror" name="password"--}}
{{--                                       autocomplete="current-password">--}}

{{--                                @error('password')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="mb-4">--}}
{{--                                <input class="form-check-input" type="checkbox" name="remember"--}}
{{--                                       id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                <label class="form-check-label" for="remember">--}}
{{--                                    {{ __('Remember Me') }}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            @if (Route::has('password.request'))--}}
{{--                                <a class="color-brand text-decoration-none fw-bolder mt-3 text-end d-block" href="{{ route('password.request') }}">--}}
{{--                                    {{ __('Forgot Your Password?') }}--}}
{{--                                </a>--}}
{{--                            @endif--}}
                            @if (session('error'))
                                <div id="error" class="error text-danger pl-3 my-5" for="error" style="display: block;">
                                    <strong>{{ session('error') }}</strong>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-brand w-100 b-radius-16 py-12 px-4 mt-40">Login
                            </button>
                            <div class="text-center mt-32">
                                <a class="btn google-link" href="/login/oauth/google"><i class="icon icon-google me-2"></i> Sign in with Google</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade service-modal" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="serviceModalLabel">Please select your preference for Wash</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div v-if="postData.services && postData.services.length > 0" class="col-6" v-for="(category, i) in postData.services[0].childCategories" :key="category.id">
                                <label class="card border-0" :class="{outline: !isServiceSelected(1, category.id)}" :for="category.id">
                                    <div class="card-body text-center">
                                        <div class="box-checkbox">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <template v-if="i === 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="113" height="113" viewBox="0 0 113 113" fill="none">
                                                <circle cx="56.5" cy="56.499" r="34.5" fill="#EF570D" fill-opacity="0.4"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M65.6026 33.1901C66.3585 34.8384 67.6052 35.9337 69.0225 36.3764C70.0593 36.6696 70.7897 36.7323 71.5245 36.6476C73.6891 36.4195 75.5843 34.9918 76.3842 32.9851C76.4122 32.9188 76.4169 32.8365 76.4093 32.7647C76.3786 32.5286 76.1778 32.3186 75.9425 32.2799C75.827 32.2611 75.7066 32.2596 75.5905 32.2593C75.5254 32.2594 75.4616 32.2634 75.3977 32.2674C75.3339 32.2714 75.27 32.2754 75.2049 32.2755C73.8743 32.2985 72.5682 32.1194 71.2826 31.8022C70.0788 31.5082 68.9145 31.0907 67.802 30.5393C67.2645 30.2712 66.7398 29.9743 66.2426 29.6294C66.1343 29.5524 66.007 29.4932 65.8828 29.4395C65.5723 29.3052 65.2259 29.4951 65.1704 29.8229C65.148 29.9512 65.1288 30.085 65.115 30.2158C65.0173 31.2512 65.1725 32.2468 65.6026 33.1901ZM48.6417 64.9341L60.3608 68.2485L72.0398 71.5516C72.0626 71.558 72.0852 71.5649 72.1078 71.5718C72.153 71.5856 72.1982 71.5993 72.2445 71.6095C72.8093 71.7426 73.3937 71.5 73.7087 70.9994C73.8143 70.8253 73.8757 70.6387 73.9223 70.4435C74.138 69.5734 74.3549 68.6992 74.5746 67.8303C74.6395 67.5699 74.7057 67.3088 74.772 67.0471C74.8365 66.7926 74.9011 66.5377 74.9647 66.2824L75.7822 63.0242L76.1834 61.4218L76.9683 58.2786C77.0686 57.8773 77.1702 57.4753 77.272 57.0727C77.3708 56.6818 77.4698 56.2904 77.5677 55.8987C77.7096 55.3358 77.8501 54.7737 77.9907 54.2115C78.1313 53.6494 78.2718 53.0873 78.4137 52.5244C78.4846 52.2426 78.5541 51.9615 78.6236 51.681C78.6908 51.4094 78.7579 51.1383 78.8259 50.8675C78.8442 50.7982 78.8615 50.7286 78.8787 50.6589C78.9188 50.4965 78.959 50.3341 79.013 50.1755C79.1109 49.8751 79.4237 49.6887 79.7312 49.7357C80.0673 49.7865 80.4059 49.8416 80.7416 49.8962L80.8427 49.9127C81.3223 49.9878 81.8042 50.0673 82.2834 50.1462L82.4258 50.1697C82.5833 50.1951 82.7409 50.2199 82.8985 50.2448C83.2139 50.2946 83.5293 50.3444 83.8437 50.3978C84.4602 50.5013 85.0454 50.2101 85.3284 49.6384C85.5667 49.156 85.8052 48.6727 86.0438 48.1893C86.2824 47.7058 86.521 47.2223 86.7593 46.7399C87.4243 45.385 88.0944 44.0271 88.7633 42.6734C88.8627 42.4753 88.9167 42.2689 88.9146 42.0466C88.907 41.5523 88.6999 41.1657 88.3066 40.886C87.1417 40.0554 85.9764 39.2239 84.8107 38.3923C83.0166 37.1123 81.2218 35.8317 79.4265 34.5526C79.3862 34.5237 79.346 34.4949 79.306 34.4662C79.1526 34.3562 79.0006 34.2471 78.8453 34.1399C78.6564 34.0111 78.4456 34.0668 78.337 34.2666L78.3076 34.3248C78.285 34.3687 78.263 34.4127 78.2409 34.4567C78.1967 34.5447 78.1526 34.6327 78.1049 34.7197C77.1806 36.4092 75.8469 37.6459 74.1045 38.4123C72.531 39.1023 70.8983 39.2657 69.2272 38.9217C68.4415 38.7571 67.6964 38.4799 66.9958 38.0911C65.7975 37.424 64.822 36.5053 64.0826 35.334C63.1754 33.8981 62.7479 32.314 62.7965 30.5942C62.8017 30.4041 62.8172 30.2169 62.8327 30.0298C62.8366 29.983 62.8404 29.9362 62.8441 29.8894C62.8627 29.6552 62.7094 29.4921 62.481 29.5029C62.4678 29.5036 62.4536 29.504 62.4394 29.5044C62.4252 29.5048 62.411 29.5053 62.3978 29.506C60.9412 29.6061 59.4849 29.7053 58.0287 29.8044C56.5725 29.9036 55.1163 30.0027 53.6598 30.1029C53.1889 30.1359 52.7182 30.168 52.2475 30.2C51.7768 30.232 51.3061 30.2641 50.8352 30.2972C50.1613 30.3504 49.6328 30.8704 49.5617 31.5509C49.5423 31.7249 49.5268 31.8967 49.5111 32.0708C49.5086 32.0988 49.5061 32.127 49.5035 32.1551C49.4512 32.7079 49.3999 33.2609 49.3486 33.8139C49.2973 34.3669 49.246 34.9199 49.1937 35.4727C49.0771 36.7432 48.9593 38.0179 48.8387 39.2873C48.7764 39.9214 49.1085 40.4942 49.6719 40.7245C49.9765 40.8505 50.2814 40.9756 50.5863 41.1006C50.8912 41.2256 51.1961 41.3506 51.5007 41.4767L53.2475 42.1968C53.2939 42.2166 53.3413 42.2367 53.3888 42.2567C53.4362 42.2768 53.4836 42.2969 53.53 42.3166C53.8555 42.453 54.0254 42.8026 53.9227 43.1504C53.8623 43.3639 53.7948 43.5754 53.7273 43.7868C53.6935 43.8926 53.6598 43.9983 53.6269 44.1043L52.611 47.3285L52.1193 48.8831L51.1243 52.0335L50.6175 53.6415C50.2691 54.7356 49.9246 55.8309 49.5802 56.9261C49.2653 57.9321 48.9465 58.9369 48.6276 59.9418L48.6275 59.9421L47.7247 62.8126C47.7146 62.8452 47.7043 62.8775 47.6942 62.9097C47.6547 63.0348 47.6158 63.1577 47.5871 63.2836C47.4621 63.8024 47.6737 64.3722 48.0933 64.6815C48.2622 64.8046 48.445 64.8785 48.6417 64.9341Z" fill="#F6F8FF"/>
                                                <path d="M93.2018 51.8106C93.8174 56.6303 93.4778 61.5242 92.2022 66.2126C90.9266 70.9011 88.74 75.2924 85.7673 79.1359C82.7946 82.9793 79.0939 86.1996 74.8767 88.6128C70.6595 91.0261 66.0082 92.5851 61.1884 93.2008C56.3687 93.8165 51.4749 93.4768 46.7864 92.2012C42.0979 90.9256 37.7066 88.739 33.8632 85.7663C30.0197 82.7936 26.7994 79.093 24.3862 74.8757C21.9729 70.6585 20.4139 66.0072 19.7982 61.1875C19.1826 56.3677 19.5222 51.4739 20.7978 46.7854C22.0734 42.0969 24.26 37.7056 27.2327 33.8622C30.2054 30.0188 33.9061 26.7985 38.1233 24.3852C42.3406 21.9719 46.9918 20.413 51.8116 19.7973C56.6313 19.1816 61.5251 19.5212 66.2136 20.7968C70.9021 22.0725 75.2934 24.259 79.1368 27.2317C82.9803 30.2045 86.2006 33.9051 88.6138 38.1223C91.0271 42.3396 92.5861 46.9909 93.2018 51.8106L93.2018 51.8106Z" stroke="#EF570D" stroke-opacity="0.5"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M35.3238 56.6998L48.084 55.1245L60.8951 53.5429C61.3694 53.4843 61.4974 53.2977 61.4159 52.7888C61.3422 52.3287 61.2687 51.8683 61.1952 51.4079C61.0482 50.4873 60.9012 49.5665 60.7525 48.6468C60.6744 48.1716 60.4836 48.0158 60.0426 48.0703L55.7487 48.6004C52.1986 49.0386 48.6486 49.4774 45.0988 49.9162C41.5489 50.355 37.9993 50.7938 34.4501 51.2319C33.9111 51.2985 33.7909 51.4713 33.8831 52.0472L34.5257 56.0594C34.618 56.6353 34.7809 56.7668 35.3238 56.6998ZM49.8229 56.8219L49.4584 56.8669L49.4584 56.867C49.4802 57.0031 49.4967 57.1057 49.515 57.208C49.5768 57.5938 49.6391 57.9794 49.7014 58.3649C49.8467 59.2641 49.9919 60.1628 50.1305 61.0634C50.2517 61.845 49.7248 62.461 49.0131 62.3973C48.5041 62.3512 48.0817 61.91 47.9821 61.3009C47.8247 60.3368 47.6708 59.3735 47.5168 58.4097C47.4655 58.0885 47.4142 57.7673 47.3628 57.446L47.3141 57.1423C47.2803 57.1443 47.2513 57.1458 47.2256 57.1471C47.1745 57.1496 47.1363 57.1515 47.0985 57.1561C45.167 57.3946 43.2359 57.6324 41.3051 57.8703C39.3745 58.1081 37.4441 58.3459 35.5138 58.5842C35.1023 58.635 34.9737 58.8302 35.0464 59.2847L38.5911 81.4169C38.6642 81.8734 38.8639 82.0345 39.2852 81.9825L43.1146 81.5098C45.2253 81.2492 47.336 80.9886 49.4464 80.7259C49.9442 80.6645 50.0485 80.5299 50.062 79.9901C50.08 79.2253 50.1033 78.4613 50.1266 77.6975C50.1322 77.5159 50.1377 77.3343 50.1432 77.1527C50.1979 75.3195 50.2544 73.4876 50.3108 71.6547C50.3205 71.3424 50.3301 71.0301 50.3397 70.7177C50.3469 70.5045 50.3564 70.2917 50.3658 70.0788C50.3723 69.932 50.3788 69.7852 50.3846 69.638C50.4494 69.6685 50.4679 69.6961 50.4788 69.7268C51.0119 71.1964 51.5428 72.6641 52.0739 74.1339C52.309 74.7856 52.5438 75.4375 52.7786 76.0895C53.2083 77.2826 53.6382 78.476 54.0695 79.6683C54.1948 80.0137 54.3694 80.1182 54.7143 80.0756C58.1165 79.6556 61.5207 79.2353 64.9225 78.8132C65.4282 78.7508 65.5486 78.5672 65.4614 78.0227L64.0924 69.4747C63.7309 67.2237 63.37 64.9732 63.009 62.7227C62.6481 60.4721 62.2872 58.2216 61.9257 55.9706C61.8509 55.5036 61.666 55.3598 61.2348 55.4131L49.8229 56.8219Z" fill="#4361EE"/>
                                            </svg>
                                        </template>
                                        <template v-if="i === 1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="113" height="113" viewBox="0 0 113 113" fill="none">
                                                <circle cx="56.5" cy="56.5" r="34.5" fill="#D1D9FF"/>
                                                <path d="M58.0096 72.3706C54.0897 73.421 50.1697 74.4713 46.2458 75.5227C46.0484 75.5756 45.8535 75.6057 45.6454 75.5864C45.1265 75.5355 44.6531 75.1544 44.4949 74.6447C44.4449 74.4903 44.411 74.3315 44.3719 74.1697C44.1406 73.1937 43.9094 72.2177 43.6781 71.2417C43.4375 70.2152 43.197 69.1886 42.9525 68.1632C42.6877 67.046 42.4229 65.9288 42.1621 64.8105C42.0324 64.2622 41.9039 63.718 41.7743 63.1697C41.5216 62.0979 41.2689 61.026 41.0163 59.9541C40.8922 59.4264 40.7669 58.8946 40.6428 58.3669C40.3835 57.2704 40.1242 56.1738 39.8649 55.0772C39.7869 54.7536 39.7169 54.4278 39.6307 54.1064C39.5409 53.755 39.216 53.5417 38.8666 53.5912C38.7666 53.6047 38.6625 53.6193 38.5625 53.6329C37.9395 53.7247 37.3164 53.8166 36.6933 53.9085C36.0409 54.0038 35.3896 54.1032 34.7373 54.1985C34.1354 54.2891 33.5569 53.9671 33.2858 53.3904C32.7396 52.2382 32.1882 51.083 31.638 49.9318C31.1619 48.9286 30.6818 47.9266 30.2058 46.9234C30.1176 46.7394 30.0306 46.5596 29.9464 46.3745C29.6592 45.7536 29.8469 45.0363 30.3986 44.6456C31.1739 44.1066 31.9503 43.5717 32.7256 43.0327C35.1254 41.3693 37.5263 39.7101 39.9262 38.0468C39.9482 38.032 39.9741 38.0162 39.9961 38.0015C40.1868 37.8753 40.402 37.937 40.5059 38.1476C40.6099 38.3583 40.7098 38.57 40.8258 38.7774C41.6645 40.2797 42.8426 41.4216 44.3571 42.1907C45.5918 42.8184 46.9001 43.1083 48.271 43.0678C49.0718 43.0431 49.8538 42.8998 50.613 42.639C52.2247 42.0791 53.5435 41.1029 54.5419 39.7046C55.6464 38.1543 56.159 36.4092 56.0881 34.4847C56.0828 34.336 56.0694 34.1894 56.0601 34.0417C56.0586 34.02 56.0571 33.9984 56.0556 33.9767C56.0465 33.7494 56.1991 33.5937 56.4274 33.6076C56.6648 33.6235 56.8993 33.6446 57.1378 33.6646C60.7623 33.9611 64.3879 34.2617 68.0083 34.5592C68.4894 34.5982 68.8652 34.8243 69.1248 35.2451C69.2404 35.4349 69.2997 35.6399 69.3157 35.8609C69.4341 37.3662 69.5536 38.8757 69.676 40.38C69.7605 41.4528 69.8461 42.5297 69.9306 43.6024C69.9801 44.2384 69.6265 44.7881 69.0439 45.0149C68.5979 45.1874 68.154 45.3682 67.7091 45.5448C67.2129 45.7396 66.7127 45.9355 66.2176 46.1344C65.8701 46.2717 65.5186 46.41 65.1722 46.5514C64.884 46.6684 64.7107 46.9887 64.7804 47.2969C64.8301 47.5309 64.9081 47.7573 64.9739 47.987C65.1369 48.5308 65.3039 49.0735 65.468 49.6214C65.8006 50.7337 66.1361 51.8408 66.4687 52.9531C66.7049 53.7379 66.937 54.5237 67.1721 55.3043C67.4837 56.3383 67.7953 57.3723 68.1069 58.4063C68.2655 58.9336 68.424 59.4609 68.5826 59.9882C68.9041 61.0593 69.2268 62.1345 69.5483 63.2056C69.7024 63.7164 69.8526 64.2283 70.0056 64.735C70.2617 65.5939 70.5228 66.4559 70.7829 67.3137C70.8429 67.5053 70.8856 67.697 70.884 67.9006C70.8698 68.4919 70.4919 68.9995 69.9386 69.1743C69.8712 69.1967 69.8016 69.211 69.7331 69.2293C65.8212 70.2775 61.9134 71.3246 58.0096 72.3706Z" fill="#FDFDFD"/>
                                                <path d="M49.1349 40.5575C47.6908 40.9028 46.0591 40.6001 44.566 39.5711C43.7136 38.981 43.0706 38.2052 42.6245 37.2657C42.5694 37.1463 42.5174 37.0215 42.4709 36.8998C42.3508 36.5898 42.5512 36.2494 42.8867 36.2057C43.0208 36.1883 43.1605 36.174 43.293 36.1847C43.8966 36.2265 44.4993 36.213 45.0983 36.1681C46.3362 36.0722 47.5501 35.8349 48.7348 35.4712C50.0015 35.0855 51.2151 34.5707 52.3465 33.8698C52.4581 33.8029 52.5577 33.7254 52.6694 33.6585C52.7693 33.5993 52.8734 33.539 52.9823 33.496C53.2042 33.4088 53.4842 33.4864 53.6315 33.6735C53.6748 33.7313 53.7129 33.8043 53.7227 33.8757C54.0629 36.009 53.1657 38.2057 51.423 39.5097C50.8351 39.9586 50.1757 40.2786 49.1349 40.5575Z" fill="white"/>
                                                <path d="M59.7672 81.2782C56.2527 79.2491 52.7382 77.22 49.22 75.1888C49.043 75.0866 48.8839 74.9701 48.7504 74.8093C48.4196 74.4064 48.3543 73.8021 48.6028 73.3299C48.6767 73.1853 48.765 73.0491 48.8518 72.907C49.3784 72.0534 49.905 71.1997 50.4315 70.346C50.9873 69.45 51.5431 68.5541 52.0953 67.6561C52.6981 66.6789 53.3008 65.7016 53.9071 64.7265C54.2032 64.2471 54.4971 63.7714 54.7931 63.2921C55.3724 62.3555 55.9516 61.4189 56.5309 60.4823C56.8162 60.0214 57.1037 59.5568 57.3891 59.0959C57.9812 58.1372 58.5732 57.1784 59.1653 56.2197C59.3389 55.9356 59.5198 55.6558 59.6861 55.3676C59.8711 55.0556 59.7922 54.6751 59.5101 54.4629C59.4298 54.4018 59.3459 54.3386 59.2656 54.2774C58.7601 53.9018 58.2546 53.5262 57.749 53.1506C57.2203 52.7567 56.6895 52.3664 56.1608 51.9725C55.6712 51.611 55.4898 50.9742 55.7059 50.3748C56.1344 49.1738 56.5614 47.9671 56.9863 46.764C57.359 45.718 57.7281 44.67 58.1008 43.624C58.1686 43.4316 58.2342 43.2428 58.3056 43.0525C58.5415 42.4103 59.1815 42.0359 59.8478 42.1497C60.7772 42.3168 61.7044 42.4875 62.6337 42.6546C65.5069 43.1754 68.3778 43.6999 71.2509 44.2206C71.2769 44.2258 71.3064 44.233 71.3324 44.2381C71.5564 44.2837 71.665 44.4794 71.5895 44.7019C71.5141 44.9243 71.435 45.1447 71.3704 45.3734C70.9011 47.0287 70.9268 48.6693 71.4538 50.284C71.8831 51.6009 72.6032 52.731 73.6012 53.6717C74.1849 54.2205 74.8392 54.6722 75.5604 55.0246C77.096 55.7683 78.7188 56.0106 80.4135 55.7278C82.2908 55.4126 83.8872 54.541 85.1979 53.1301C85.2993 53.0212 85.3935 52.9081 85.4913 52.797C85.5056 52.7806 85.5199 52.7643 85.5341 52.7479C85.6884 52.5808 85.9064 52.5785 86.058 52.7498C86.2146 52.9289 86.3655 53.1096 86.5201 53.2925C88.8733 56.065 91.2244 58.8412 93.5741 61.6117C93.8867 61.9794 93.9925 62.405 93.8786 62.8861C93.8261 63.1021 93.723 63.2889 93.5781 63.4565C92.5974 64.6047 91.6145 65.7565 90.6373 66.9068C89.9385 67.7251 89.2376 68.5471 88.5388 69.3654C88.1241 69.8501 87.4854 69.9888 86.913 69.7372C86.4757 69.5438 86.034 69.3578 85.5945 69.1681C85.1059 68.9549 84.6137 68.7397 84.123 68.5303C83.7802 68.3817 83.4338 68.2309 83.0888 68.086C82.8023 67.9649 82.4534 68.0689 82.2847 68.336C82.1544 68.5367 82.0494 68.7519 81.9335 68.9609C81.6642 69.4607 81.3986 69.9625 81.1272 70.466C80.5759 71.4876 80.0303 72.5077 79.4789 73.5294C79.091 74.2513 78.6995 74.9711 78.3137 75.6894C77.8029 76.6408 77.2921 77.5923 76.7813 78.5438C76.5206 79.0288 76.2598 79.5137 75.9991 79.9987C75.4691 80.9834 74.937 81.9719 74.407 82.9566C74.1548 83.4268 73.8989 83.8949 73.6489 84.3614C73.2226 85.1498 72.7977 85.944 72.3751 86.7345C72.282 86.9124 72.1767 87.0782 72.0316 87.221C71.6034 87.629 70.9773 87.7208 70.4624 87.4531C70.3989 87.4213 70.3396 87.3822 70.2782 87.3467C66.7709 85.3218 63.2673 83.2989 59.7672 81.2782Z" fill="white"/>
                                                <path d="M75.9897 52.5099C74.7243 51.7329 73.7846 50.3651 73.4565 48.5817C73.271 47.5617 73.365 46.5585 73.7138 45.5787C73.7592 45.4553 73.8108 45.3303 73.8639 45.2114C73.9982 44.9072 74.3806 44.8082 74.6487 45.0146C74.7559 45.0971 74.8648 45.1857 74.9508 45.287C75.3482 45.7434 75.7839 46.16 76.2392 46.5518C77.1823 47.3594 78.2085 48.0499 79.3033 48.6304C80.4718 49.2534 81.6939 49.7475 82.9895 50.0519C83.1158 50.0836 83.241 50.0991 83.3673 50.1308C83.4797 50.1596 83.5961 50.1906 83.7034 50.2371C83.922 50.3324 84.0652 50.5853 84.037 50.8218C84.0267 50.8932 84.002 50.9718 83.9585 51.0292C82.6906 52.7783 80.5029 53.6971 78.3486 53.3869C77.6154 53.2886 76.9228 53.0486 75.9897 52.5099Z" fill="#F6F8FF"/>
                                                <path d="M93.2018 51.8116C93.8174 56.6313 93.4778 61.5251 92.2022 66.2136C90.9266 70.9021 88.74 75.2934 85.7673 79.1368C82.7946 82.9803 79.0939 86.2006 74.8767 88.6138C70.6595 91.0271 66.0082 92.5861 61.1884 93.2018C56.3687 93.8174 51.4749 93.4778 46.7864 92.2022C42.0979 90.9266 37.7066 88.74 33.8632 85.7673C30.0197 82.7946 26.7994 79.0939 24.3862 74.8767C21.9729 70.6594 20.4139 66.0082 19.7982 61.1884C19.1826 56.3687 19.5222 51.4749 20.7978 46.7864C22.0734 42.0979 24.26 37.7066 27.2327 33.8632C30.2054 30.0197 33.9061 26.7994 38.1233 24.3862C42.3406 21.9729 46.9918 20.4139 51.8116 19.7982C56.6313 19.1826 61.5251 19.5222 66.2136 20.7978C70.9021 22.0734 75.2934 24.26 79.1368 27.2327C82.9803 30.2054 86.2006 33.9061 88.6138 38.1233C91.0271 42.3406 92.5861 46.9918 93.2018 51.8116L93.2018 51.8116Z" stroke="#A0B0FD"/>
                                            </svg>
                                        </template>

                                        <h6>@{{category.category_name}}</h6>
                                        <input
                                            type="radio"
                                            name="washType"
                                            :value="category.id"
                                            :id="category.id"
                                            class="btn-check"
                                            :checked="selectedCategory == category.id"
                                            @change="selectedCategoryId(category.id)"
                                        >
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-brand w-100" @click.prevent="addService()">ADD SERVICE</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="flashVisible" class="alert alert-danger error-toast">
            @{{ errors.magic_link }}
        </div>

        <div v-if="sevicveFlash" class="alert alert-success success-toast">
            @{{ sevicveFlashMessage }}
        </div>

    </div>
@endsection

@section('script')
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    isButtonDisabled: false,
                    addressMessage: "",
                    manualAuth: false,
                    validEmail: true,
                    emailCheckMessage: "",

                    // services: [],
                    currentStep: 1,
                    totalSteps: 7,
                    isManualAddress: false,
                    addresses: null,
                    isLoading: false,
                    isDateTimeChecked: false,
                    dateTimeSlot: {},
                    isCheckUserLoggedIn: false,
                    isCheckLoggedIn: "",
                    user: {},
                    login: {
                        email: "",
                        password: "",
                    },

                    addressDetails: {
                        firstLine: "",
                        secondLine: "",
                    },

                    // Post Data
                    postData: {
                        customer_id: '',

                        address: '',
                        type: 1,
                        city: '',
                        country: '',
                        postcode: 'se17rj',
                        order_location: 0,
                        manual_address: '',

                        services: [],

                        pickup_date: '',
                        pickup_time: '',
                        collection_instructions: '',
                        delivery_date: '',
                        delivery_time: '',
                        delivery_instructions: '',
                        other_requests: '',
                        promo_code: '',

                        selectedServices: [],

                        // Contact Details
                        customer_type: '',
                        customer_name: '',
                        uFName: '',
                        uLName: '',
                        uCompanyName: '',
                        taxNumber: '',
                        email: '',
                        phone_code: '+44',
                        phone_number: '',
                    },
                    errors: {
                        magic_link: ""
                    },
                    flashVisible: false,
                    sevicveFlash: false,
                    sevicveFlashMessage:'',
                    tempDataLoaded: false,
                    formErrors: {},
                    selectedCategory: null,
                }
            },
            created() {
                const urlParams = new URLSearchParams(window.location.search);
                const step = parseInt(urlParams.get('step'));
                const errorMessage = @json(session('errors', new \Illuminate\Support\ViewErrorBag)->first('magic_link'));
                if (errorMessage) {
                    this.errors.magic_link = errorMessage;
                    this.flashVisible = true;
                    setTimeout(() => {
                        this.flashVisible = false;
                    }, 3000);
                }
                this.currentStep = isNaN(step) ? 1 : step;
            },
            computed: {
                postDataJson() {
                    return JSON.stringify(this.postData);
                }
            },
            mounted: function () {
                this.selectServicesOnMount();
                // this.restorePostDataFromSessionStorage();
                // this.restoreDataFromSessionStorage();
                if (!this.tempDataLoaded && @json(session('magic_token'))) {
                    this.getTempData();
                    this.tempDataLoaded = true;
                    console.log('if');
                }else {
                    this.restorePostDataFromSessionStorage();
                    console.log('else');
                }
                if (@json(session('postcode'))) {
                    this.postData.postcode = @json(session('postcode'));
                    console.log('postcode : '+this.postData.postcode);
                    this.findAddress();
                }
                this.removeQueryParamsFromURL();
                window.history.replaceState({}, '', `?step=${this.currentStep}`);
                this.getDateTime();
                this.getUpdatedDateTime();
                if (this.postData.services && this.postData.services.length > 0 && this.postData.services[0].childCategories.length > 0) {
                    this.selectedCategory = this.postData.services[0].childCategories[0].id;
                }

                (async () => {
                    await this.delay(5000); // Adjust the delay time as needed
                    if(!this.checkDateTimeSlots()){
                        this.currentStep == 2;
                        window.history.replaceState({}, '', `?step=${this.currentStep}`);
                    }
                })();


            },
            watch: {
                currentStep(newStep) {
                    if (newStep === 4) {
                        if(!this.postData.selectedServices.length){
                            return this.currentStep = 3;
                        }
                        this.checkLoginAfterRedirect();
                        console.log('watched');

                    }
                }
            },
            methods: {
                delay(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                },
                selectServicesOnMount() {
                    const url = window.location.href;
                    const params = new URL(url).searchParams;

                    if (params.get('services') || params.get('selectedServices')) {
                    const selectedServicesParam = params.get('selectedServices');
                    const servicesParam = params.get('services');
                        console.log(params);

                        this.postData.selectedServices = JSON.parse(selectedServicesParam);
                        this.postData.services = JSON.parse(servicesParam);
                    }else{
                        this.getServices();
                    }
                },
                getServices(){
                    axios.get('/api/services')
                        .then((res) =>{
                            console.log(res.data);
                            this.postData.services = res.data.services;

                        })
                        .catch((error) => {
                            console.error(error);
                        });

                },
                addService() {
                    const selectedCategory = this.selectedCategory;
                    console.log(selectedCategory);
                    if (selectedCategory) {
                        this.toggleService(1, selectedCategory,'Wash');
                    }
                    this.closeServiceModal();
                },
                selectedCategoryId(id){
                    this.selectedCategory = id;
                },
                validateEmail() {
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    this.validEmail = emailRegex.test(this.postData.email);
                    if (this.validEmail) {
                        this.formErrors.email = '';
                        this.checkEmail();
                    }else{
                        this.formErrors.email = "Invalid Email";
                    }
                },
                checkEmail() {
                    axios.get(`/api/check-email?email=${this.postData.email}`)
                        .then((res) => {
                            console.log(res.data);
                            this.emailCheckMessage = res.data.message;
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },
                showLoginModal() {
                    $('#loginModal').modal('show');
                },
                openServiceModal() {
                    $('#serviceModal').modal('show');
                },
                checkLoginAfterRedirect() {
                    if (this.currentStep === 4) {
                        this.isUserLoggedIn();
                        this.isCheckUserLoggedIn = true;

                    }
                },
                getTempData(){
                    console.log('token : ' +@json(session('magic_token')) );
                    axios.get(`api/get-temp-data/${@json(session('magic_token'))}`)
                        .then((res) => {
                            if (res.data.temp_data) {
                                this.postData = res.data.temp_data;
                            } else {
                                this.restorePostDataFromSessionStorage();
                            }
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },
                checkDateTimeSlots() {
                    // Check if this.dateTimeSlots is defined and not null
                    if (!this.dateTimeSlot) {
                        return false;
                    }

                    if (
                        !Object.values(this.dateTimeSlot.pickUpDates).includes(this.postData.pickup_date) ||
                        !Object.values(this.dateTimeSlot.pickUpTimeSlots).includes(this.postData.pickup_time) ||
                        !Object.values(this.dateTimeSlot.deliveryDates).includes(this.postData.delivery_date) ||
                        !Object.values(this.dateTimeSlot.deliveryTimeSlots).includes(this.postData.delivery_time)
                    ) {

                        return false; // Return false if any value is missing
                    }

                    // All values are present
                    return true; // Return true if all values are present
                },
                performDateTimeSlotsCheck() {
                    const areValuesPresent = this.checkDateTimeSlots();
                    console.log('here');
                    // Check if any values are missing
                    if (!areValuesPresent) {
                        // Redirect the user to step=2
                        this.isDateTimeChecked = true;
                        this.currentStep = 2; // Replace with your actual step 2 path
                    }
                },
                isUserLoggedIn() {
                    axios.get(`${window.location.origin}/api/auth-check/`)
                        .then((res) => {
                            console.log(res.data);
                            this.isCheckUserLoggedIn = res.data.isLoggedIn;
                            console.log(this.isCheckUserLoggedIn);
                            this.user = res.data.user;

                            this.postData.customer_id = this.user.id;
                            this.postData.customer_type = this.user.customer_type;
                            this.postData.uFName = this.user.uFName;
                            this.postData.uLName = this.user.uLName;
                            this.postData.email = this.user.email;
                            this.postData.phone_number = this.user.phone_number;
                            console.log(this.user);
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },
                restorePostDataFromSessionStorage() {
                    const commonKey = 'formData';
                    const storedData = sessionStorage.getItem(commonKey);

                    if (storedData !== null) {
                        this.postData = JSON.parse(storedData);
                        if (this.postData.postcode) {
                            this.findAddress();
                        }
                        console.log('Restored postData:', this.postData);
                    }
                },
                restoreDataFromSessionStorage() {
                    const storedData = sessionStorage.getItem('queryParams');
                    const selectedServices = sessionStorage.getItem('formData');


                    console.log(storedData);
                    if (selectedServices) {
                        const querySelectedServices = JSON.parse(selectedServices);
                        if (querySelectedServices.selectedServices) {
                            selectedServicesJson = JSON.stringify(querySelectedServices.selectedServices);
                            this.postData.selectedServices = JSON.parse(selectedServicesJson);
                        }
                    }

                    if (storedData) {
                        const queryParamsObject = JSON.parse(storedData);
                        if (queryParamsObject.services) {
                            this.postData.services = JSON.parse(queryParamsObject.services);
                        } else {
                            console.log('no service in sessionStorage');
                        }
                    }
                },
                removeQueryParamsFromURL() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const queryParamsObject = {};
                    if (urlParams.has('selectedServices') || urlParams.has('services')) {
                        queryParamsObject['selectedServices'] = urlParams.get('selectedServices');
                        queryParamsObject['services'] = urlParams.get('services');

                        sessionStorage.setItem('queryParams', JSON.stringify(queryParamsObject));

                        urlParams.delete('selectedServices');
                        urlParams.delete('services');
                        const newURL = window.location.origin + window.location.pathname + '?' + urlParams.toString();
                        window.history.replaceState({}, document.title, newURL);
                    }
                },
                nextStep() {
                    if (this.currentStep === 3) {
                        this.isUserLoggedIn();
                        if (this.isCheckLoggedIn) {
                            this.currentStep = Math.min(this.currentStep + 1);
                            window.history.replaceState({}, '', `?step=${this.currentStep}`);
                            window.scrollTo({top: 0, behavior: 'smooth'});
                            this.isCheckUserLoggedIn = true
                        } else {
                            this.currentStep = Math.min(this.currentStep + 1);
                            window.history.replaceState({}, '', `?step=${this.currentStep}`);
                            window.scrollTo({top: 0, behavior: 'smooth'});

                            const commonKey = 'formData';
                            let dataToStore = this.postData;
// Remove sensitive user-specific fields if not logged-in
                            if (!this.isCheckUserLoggedIn){
                                dataToStore.customer_id = '';
                                dataToStore.uFName = '';
                                dataToStore.uLName = '';
                                dataToStore.email = '';
                                dataToStore.phone_number = '';
                            }
                            sessionStorage.setItem(commonKey, JSON.stringify(dataToStore));

                        }
                    } else {
                        if (this.currentStep === 4) {

                            if (!this.emailCheckMessage) {
                                const validation = this.validateInput();
                                if (validation.isValid){

                                    const formDataSend = {
                                        customer_type: this.postData.customer_type,
                                        uFName: this.postData.uFName,
                                        uLName: this.postData.uLName,
                                        email: this.postData.email,
                                        phone_code: this.postData.phone_code,
                                        phone_number: this.postData.phone_number,
                                        uCompanyName: this.postData.uCompanyName,
                                        taxNumber: this.postData.taxNumber,
                                    };
                                    axios.post('/api/register-email-only', formDataSend)
                                        .then((res) => {
                                            console.log(res.data.message);
                                            this.user = res.data.user;
                                            this.postData.customer_id = this.user.id;
                                            // window.location.href = res.data.redirectTo;
                                            console.log(this.postData.customer_id);
                                        })
                                        .catch((error) => {
                                            console.error(error);
                                        });
                                } else {
                                    console.log(validation.errors);

                                    return;
                                }
                            }
                        }
                        this.currentStep = Math.min(this.currentStep + 1);
                        window.history.replaceState({}, '', `?step=${this.currentStep}`);
                        window.scrollTo({top: 0, behavior: 'smooth'});

                        const commonKey = 'formData';
                        let dataToStore = this.postData;
// Remove sensitive user-specific fields if not logged-in
                        if (!this.isCheckUserLoggedIn){
                            dataToStore.customer_id = '';
                            dataToStore.uFName = '';
                            dataToStore.uLName = '';
                            dataToStore.email = '';
                            dataToStore.phone_number = '';
                        }
                        sessionStorage.setItem(commonKey, JSON.stringify(dataToStore));
                    }
                },
                prevStep() {
                    this.currentStep = Math.max(this.currentStep - 1, 1);
                    window.history.replaceState({}, '', `?step=${this.currentStep}`);
                    window.scrollTo({top: 0, behavior: 'smooth'});
                },
                validateInput() {
                    let isValid = true;
                    const errors = {};

                    // Check required fields for any customer_type
                    if (!this.postData.uFName.trim()) {
                        isValid = false;
                        errors.uFName = 'First name is required';
                    }
                    if (!this.postData.uLName.trim()) {
                        isValid = false;
                        errors.uLName = 'Last name is required';
                    }
                    if (!this.postData.email.trim()) {
                        isValid = false;
                        errors.email = 'Email is required';
                    }
                    if (!this.postData.phone_number.trim()) {
                        isValid = false;
                        errors.phone_number = 'Phone number is required';
                    }

                    // Additional checks if customer_type is 2
                    if (this.postData.customer_type == 2) {
                        if (!this.postData.uCompanyName.trim()) {
                            isValid = false;
                            errors.uCompanyName = 'Company name is required';
                        }
                        if (!this.postData.taxNumber.trim()) {
                            isValid = false;
                            errors.taxNumber = 'Tax number is required';
                        }
                    }
                    this.formErrors = errors;
                    // Return an object with the validity status and any error messages
                    return {
                        isValid,
                        errors
                    };
                },
                findAddress: function () {
                    if (this.postData.postcode !== '') {
                        this.isLoading = true;

                        axios.get(`/api/checkService/${this.postData.postcode}`)
                            .then((res) => {
                                this.isLoading = false;

                                let formattedPostcode = res.data.postcode;
                                formattedPostcode = formattedPostcode.toUpperCase();
                                // if (formattedPostcode.length > 3) {
                                //     formattedPostcode = formattedPostcode.substring(0, 3) + ' ' + formattedPostcode.substring(3);
                                // }
                                // if (formattedPostcode.length > 8) {
                                //     formattedPostcode = formattedPostcode.substring(0, 8);
                                // }

                                this.postData.postcode = formattedPostcode;
                                this.postData.city = res.data.city;
                                this.postData.country = res.data.country;
                                this.postData.order_location = res.data.order_location;
                                this.addressMessage = res.data.message;
                                this.addresses = res.data.addresses;

                                if (this.postData.postcode) {
                                    this.getDateTime();
                                    this.isButtonDisabled = true;
                                } else {
                                    this.isButtonDisabled = false;
                                }
                            })
                            .catch((error) => {
                                this.isLoading = false;
                                console.error(error);
                            });
                    }
                },
                getDateTime: function () {
                    axios.get(`/api/timeslots/${this.postData.postcode}`)
                        .then((res) => {
                            this.isLoading = false;
                            this.dateTimeSlot = res.data;
                            console.log(this.dateTimeSlot);
                            if (!this.postData.pickup_date) {
                                this.postData.pickup_date = this.dateTimeSlot.pickUpDates[Object.keys(this.dateTimeSlot.pickUpDates)[0]];
                            }
                            this.postData.pickup_time = this.dateTimeSlot.pickUpTimeSlots[0];
                            this.postData.delivery_date = this.dateTimeSlot.deliveryDates[Object.keys(this.dateTimeSlot.deliveryDates)[0]];
                            this.postData.delivery_time = this.dateTimeSlot.deliveryTimeSlots[0];
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },
                getUpdatedDateTime: function (option = 'all') {
                    let previousPickupTime = this.postData.pickup_time;
                    let previousDeliveryDate = this.postData.delivery_date;
                    let previousDeliveryTime = this.postData.delivery_time;
                    let newDate = '';
                    axios.get(`/api/updatedtimeslots/${encodeURIComponent(this.postData.postcode)}?pickup_date=${this.postData.pickup_date}&delivery_date=${this.postData.delivery_date}&pickup_time=${encodeURIComponent(this.postData.pickup_time)}`)
                        .then((res) => {
                            this.isLoading = false;

                            if (option !== "pt") {
                                this.dateTimeSlot.pickUpTimeSlots = res.data.pickUpTimeSlots;
                                if (previousPickupTime) {
                                    this.postData.pickup_time = previousPickupTime;
                                } else {
                                    this.postData.pickup_time = this.dateTimeSlot.pickUpTimeSlots[0];
                                }

                            }
                            if (option !== "dd") {
                                this.dateTimeSlot.deliveryDates = res.data.deliveryDates;
                                if (previousDeliveryDate && Object.values(this.dateTimeSlot.deliveryDates).includes(previousDeliveryDate)) {
                                    this.postData.delivery_date = previousDeliveryDate;
                                } else {
                                    this.postData.delivery_date = this.dateTimeSlot.deliveryDates[Object.keys(this.dateTimeSlot.deliveryDates)[0]];

                                    let newDate = this.postData.delivery_date;
                                }
                            }
                            if (option !== "dt") {
                                this.dateTimeSlot.deliveryTimeSlots = res.data.deliveryTimeSlots;
                                if (previousDeliveryTime && Object.values(this.dateTimeSlot.deliveryTimeSlots).includes(previousDeliveryTime) && newDate != '') {
                                    this.postData.delivery_time = previousDeliveryTime;
                                } else {
                                    this.postData.delivery_time = this.dateTimeSlot.deliveryTimeSlots[0];
                                }
                            }

                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },
                isServiceSelected(id, sub_cat_id = null) {
                    if (this.postData.selectedServices) {
                        let selectedServices = this.postData.selectedServices.filter(function(item) {
                            if (sub_cat_id) {
                                return item.cat === id && item.sub_cat === sub_cat_id;
                            } else {
                                return item.cat === id;
                            }
                        });
                        return selectedServices.length > 0;
                    }
                    return false;
                },
                toggleService(id, subCatId = null, title) {
                    // Initialize postData.selectedServices if it doesn't exist
                    if (!this.postData.selectedServices) {
                        this.postData.selectedServices = [];
                    }

                    // Check if the service is already selected
                    const existingServiceIndex = this.postData.selectedServices.findIndex(item => item.cat === id);

                    if (existingServiceIndex !== -1) {
                        // Service already exists, update sub_cat if subCatId is provided
                        if (subCatId !== null) {
                            this.postData.selectedServices[existingServiceIndex].sub_cat = subCatId;
                        } else {
                            // Remove the service if subCatId is not provided
                            this.postData.selectedServices.splice(existingServiceIndex, 1);
                        }
                    } else {
                        // Add the service with cat and sub_cat
                        if (subCatId !== null) {
                            this.postData.selectedServices.push({ cat: id, sub_cat: subCatId });
                            this.sevicveFlashMessage = 'Service : '+title+' added.'
                            this.showServiceFlash(this.sevicveFlashMessage);
                        } else {
                            // Add the service with cat only
                            this.postData.selectedServices.push({ cat: id, sub_cat: null });
                            this.sevicveFlashMessage = 'Service : '+title+' added.'
                            this.showServiceFlash(this.sevicveFlashMessage);
                        }
                    }
                },
                showServiceFlash(message){
                    if (message != '') {
                        this.sevicveFlashMessage = message;
                        this.sevicveFlash = true;
                        setTimeout(() => {
                            this.sevicveFlash = false;
                        }, 2000);
                    }
                },
                isFirstRadio(categoryId) {
                    if (this.postData.services && this.postData.services.length > 0) {
                        return this.postData.services[0].childCategories[0].id === categoryId;
                    }
                    return false;
                },
                closeServiceModal() {
                    $('#serviceModal').modal('hide');
                },
                checkout() {
                    axios.post('/api/checkout', this.postData)
                        .then((checkoutResponse) => {
                            console.log('Checkout success');
                            // Assuming that the checkoutResponse contains data needed for the payment request
                            const paymentData = this.postData;

                            // Make the payment request
                            return axios.post('/online-payment', paymentData);
                        })
                        .then((paymentResponse) => {
                            console.log('Payment success');
                            // Handle the payment success response here
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            // Handle errors for both checkout and payment requests here
                        });
                },
            },
        })
        app.mount('#app')
    </script>
    <style scoped>
        .error-toast {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);  /* This is used to center the element */
            padding: 15px 25px;
            border-radius: 5px;
            z-index: 1000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .success-toast {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);  /* This is used to center the element */
            padding: 15px 25px;
            border-radius: 5px;
            z-index: 1000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .error-message{
            color: red;
            padding-top: 10px;
        }
    </style>
@endsection

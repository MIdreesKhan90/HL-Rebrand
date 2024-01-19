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
                            <h5>Step 1 of 5: Find your address</h5>
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
                            <h5>Step 1 of 5: Find your address</h5>
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
                        <h5>Step 2 of 5: Collection time</h5>
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
                        <h5>Step 3 of 5: Select service</h5>
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
                                                <a v-else @click.prevent="toggleService(item.id)" class="btn btn-brand py-3 px-32 ms-md-4" :class="{outline: !isServiceSelected(item.id)}" href="#">
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
                            <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</a>
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
                        <h5>Step 4 of 5: Contact details</h5>
                        <div class="progress-bar-main bg-dark-grey">
                            <div class="progress-bar-fill" style="width: 60%;"></div>
                        </div>
                        <p>Next: Payment details</p>
                        <div class="alert alert-primary d-flex justify-content-between" role="alert">
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
                                </div>
                                <div class="form-col form-col-2">
                                    <label>Last name</label>
                                    <input type="text" placeholder="Last name" v-model="postData.uLName"
                                           class="form-control" required :readonly="isCheckUserLoggedIn"/>
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
                                    </div>
                                </template>

                                <div class="form-col form-col-2">
                                    <label>Email address</label>
                                    <input type="email" @input="validateEmail" placeholder="example@gmail.com"
                                           v-model="postData.email"
                                           class="form-control" required :readonly="isCheckUserLoggedIn"/>
                                    <span v-if="!validEmail && postData.email !== ''" style="color: red;">Invalid Email</span>
                                    <p v-if="emailCheckMessage" class="mt-2 text-secondary"><small
                                            style="color: red;">@{{ emailCheckMessage
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
                        <a href="" @click.prevent="prevStep" title="back" class="arrow-back-link"><i
                                class="fa-solid fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="progress-bar-sec">
                        <h5>Step 5 of 5:Payment details</h5>

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
                            <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Check Out</a>
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
    {{--        <section class="grey-mid-sec bg-grey" v-if="currentStep === 5">--}}
        {{--            <div class="container">--}}
            {{--                <div class="row justify-content-center">--}}
                {{--                    <div class="col-sm-12 col-lg-8">--}}
                    {{--                        <div class="back-arrow">--}}
                        {{--                            <a @click="prevStep" title="back" class="arrow-back-link"><i--}}
                            {{--                                    class="fa-solid fa-arrow-left"></i> Back</a>--}}
                        {{--                        </div>--}}
                    {{--                        <div class="progress-bar-sec">--}}
                        {{--                            <h5>Step 5 of 6: Summary</h5>--}}
                        {{--                            <div class="progress-bar-main bg-dark-grey">--}}
                            {{--                                <div class="progress-bar-fill" style="width: 80%;"></div>--}}
                            {{--                            </div>--}}
                        {{--                            <p>Next: Payment details</p>--}}
                        {{--                        </div>--}}
                    {{--                        <div class="mid-white-box bg-white manual-add-block">--}}
                        {{--                            <div class="mid-white-box-title justify-content-between">--}}
                            {{--                                <h3>Order delivery details</h3>--}}
                            {{--                                <a type="button" title="Edit" class="edit-link">Edit</a>--}}
                            {{--                            </div>--}}
                        {{--                            <div class="summary-content">--}}
                            {{--                                <h6>Address</h6>--}}
                            {{--                                <h6>Collection time</h6>--}}
                            {{--                                <p>@{{ addressDetails.fullAdress.line_1 + "," + addressDetails.fullAdress.town_or_city + "," + postData.postcode}}</p>--}}
                            {{--                                <p>@{{ postData.pickup_date + " " + postData.pickup_time }}</p>--}}
                            {{--                                <h6>Delivery time</h6>--}}
                            {{--                                <p>@{{ postData.delivery_date + " " + postData.delivery_time }}</p>--}}
                            {{--                            </div>--}}
                        {{--                        </div>--}}
                    {{--                        <div class="mid-white-box bg-white manual-add-block">--}}
                        {{--                            <div class="mid-white-box-title justify-content-between">--}}
                            {{--                                <h3>Contact details</h3>--}}
                            {{--                                <a type="button" title="Edit" class="edit-link">Edit</a>--}}
                            {{--                            </div>--}}
                        {{--                            <div class="summary-content">--}}
                            {{--                                <h6>Full name</h6>--}}
                            {{--                                <p>@{{ postData.uFName + " " + postData.uLName}}</p>--}}
                            {{--                                <h6>Email address</h6>--}}
                            {{--                                <p><a href="mailto:example@gmail.com" title="mail us">@{{ postData.email }}</a></p>--}}
                            {{--                                <h6>Mobile number</h6>--}}
                            {{--                                <p><a title="call us">@{{ postData.phone_number }}</a></p>--}}
                            {{--                            </div>--}}
                        {{--                        </div>--}}
                    {{--                        <div class="next-btn-main">--}}
                        {{--                            <div class="next-btn-col">--}}
                            {{--                                <a @click="nextStep" title="Next" class="btn btn-brand d-inline-block">Next</a>--}}
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
    <section class="thanks-content bg-light" v-if="currentStep === 6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="thanks-inner bg-white ">
                        <img src="assets/images/thanks-page-img.svg" alt="" class="mb-5 ">
                        <h3 class="mb-4">Awesome!<br>Your laundry has been placed</h3>
                        <p>Hello Laundry rider is on his way to pickup your laundry.</p>
                    </div>
                    <a class="btn btn-brand  py-3 px-45 d-table ml-auto mt-40" href="{{url('/')}}">Continue</a>
                </div>
            </div>
        </div>
    </section>

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

    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="serviceModalLabel">Please select your preference for Wash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div v-if="postData.services && postData.services.length > 0" class="col-6" v-for="category in postData.services[0].childCategories" :key="category.id">
                            <div class="card border-0">
                                <div class="card-body text-center">
                                    <img src="path_to_mixed_wash_image" :alt="category.category_name" class="img-fluid mb-3">
                                    <h6>@{{category.category_name}}</h6>
                                    <input
                                        type="radio"
                                        name="washType"
                                        :value="category.id"
                                        :id="category.id"
                                        class="btn-check"
                                        :checked="isServiceSelected(1, category.id)"
                                        @change="toggleService(1, category.id)"
                                    >
                                    <label class="btn btn-brand w-100" :class="{outline: !isServiceSelected(1, category.id)}" :for="category.id">@{{ isServiceSelected(1, category.id) ? 'Selected' : 'Select'  }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-brand w-100" @click.prevent="closeServiceModal()">ADD SERVICE</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="flashVisible" class="alert alert-danger error-toast">
        @{{ errors.magic_link }}
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
            // totalEstimate() {
            //     let total = 0;
            //     this.postData.selectedServices.forEach(service => {
            //         service.products.forEach(product => {
            //             total += product.product_price * product.quantity;
            //         });
            //     });
            //     return total;
            // }
            postDataJson() {
                return JSON.stringify(this.postData);
            }
        },
        mounted: function () {
            this.selectServicesOnMount();
            // this.restorePostDataFromSessionStorage();
            // this.restoreDataFromSessionStorage();
            if (this.postData.postcode) {
                this.findAddress();
            }
            this.removeQueryParamsFromURL();
            window.history.replaceState({}, '', `?step=${this.currentStep}`);
            this.getDateTime();
            this.getUpdatedDateTime();

        },
        watch: {
            currentStep(newStep) {
                if (newStep === 4) {
                    this.checkLoginAfterRedirect();
                    console.log('watched');

                }
            }
        },
        methods: {
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
            validateEmail() {
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                this.validEmail = emailRegex.test(this.postData.email);
                if (this.validEmail) {
                    this.checkEmail();
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
                this.toggleService(1,1)
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
                        this.postData = res.data.temp_data;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            },
            checkDateTimeSlots() {
                // Check if this.dateTimeSlots is defined and not null
                if (!this.dateTimeSlots) {
                    return false;
                }

                // Check if any of the date and time values are missing in dateTimeSlots
                if (!this.dateTimeSlots.pickUpDates[this.postData.pickup_date]) {
                    // Handle missing pickup_date
                    return false;
                } else if (!this.dateTimeSlots.pickUpTimeSlots.includes(this.postData.pickup_time)) {
                    // Handle missing pickup_time
                    return false;
                } else if (!this.dateTimeSlots.deliveryDates[this.postData.delivery_date]) {
                    // Handle missing delivery_date
                    return false;
                } else if (!this.dateTimeSlots.deliveryTimeSlots.includes(this.postData.delivery_time)) {
                    // Handle missing delivery_time
                    return false;
                }

                // All values are present
                return true; // Return true if all values are present
            },
            performDateTimeSlotsCheck() {
                const areValuesPresent = this.checkDateTimeSlots();

                // Check if any values are missing
                if (!areValuesPresent) {
                    // Redirect the user to step=2
                    this.isDateTimeChecked = true;
                    window.location.href = '/booking?step=2'; // Replace with your actual step 2 path
                }
            },
            isUserLoggedIn() {
                axios.get(`/api/auth-check/`)
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
            loginAccount() {
                $('#loginModal').modal('hide');
                axios
                    .post('/api/login', this.login)
                    .then((res) => {
                        console.log(res.data);
                        // if (res.data.success) {
                        //     window.location.href = '/prices';
                        // } else {
                        //     console.log('Login failed');
                        // }
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
                        if (!this.emailCheckMessage && !this.isCheckUserLoggedIn) {
                            if (this.validateInput()) {
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
                                        window.location.href = res.data.redirectTo;
                                        console.log(this.postData.customer_id);
                                    })
                                    .catch((error) => {
                                        console.error(error);
                                    });
                            } else {
                                console.log('Validation Failed');

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
                if (
                    this.postData.uFName.trim() === '' ||
                    this.postData.uLName.trim() === '' ||
                    this.postData.email.trim() === ''
                ) {
                    return false;
                }
                return true;
            },
            findAddress: function () {
                if (this.postData.postcode !== '') {
                    this.isLoading = true;

                    axios.get(`/api/checkService/${this.postData.postcode}`)
                        .then((res) => {
                            this.isLoading = false;

                            let formattedPostcode = res.data.postcode;
                            formattedPostcode = formattedPostcode.toUpperCase();
                            if (formattedPostcode.length > 3) {
                                formattedPostcode = formattedPostcode.substring(0, 3) + ' ' + formattedPostcode.substring(3);
                            }
                            if (formattedPostcode.length > 8) {
                                formattedPostcode = formattedPostcode.substring(0, 8);
                            }

                            this.postData.postcode = formattedPostcode;
                            this.postData.city = res.data.city;
                            this.postData.country = res.data.country;
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
                        if (!this.isDateTimeChecked) {
                            this.performDateTimeSlotsCheck();
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            },

            // getServiceIcon(serviceSlug) {
            //     const matchingService = this.services.find(service => service.slug === serviceSlug);
            //     return matchingService ? matchingService.icon : '';
            // },
            // getServiceTitle(serviceSlug) {
            //     const matchingService = this.services.find(service => service.slug === serviceSlug);
            //     return matchingService ? matchingService.title : '';
            // },
            // getServicePriceSymbol(serviceSlug) {
            //     const matchingService = this.services.find(service => service.slug === serviceSlug);
            //     return matchingService ? matchingService.priceSymbol : '';
            // },
            // incrementQuantity(product) {
            //     product.quantity++;
            // },
            // decrementQuantity(product) {
            //     if (product.quantity > 1) {
            //         product.quantity--;
            //     }
            // },
            // removeProduct(service, product) {
            //     const confirmDelete = window.confirm("Are you sure you want to delete this product?");
            //
            //     if (confirmDelete) {
            //         const index = service.products.indexOf(product);
            //         if (index !== -1) {
            //             service.products.splice(index, 1);
            //         }
            //         if (service.products.length === 0) {
            //             const serviceIndex = this.postData.selectedServices.indexOf(service);
            //             if (serviceIndex !== -1) {
            //                 this.postData.selectedServices.splice(serviceIndex, 1);
            //             }
            //         }
            //     }
            // },

            // isServiceSelected(serviceSlug) {
            //     return this.selectedServices.includes(serviceSlug);
            // },
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
            toggleService(id, subCatId = null) {
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
                    } else {
                        // Add the service with cat only
                        this.postData.selectedServices.push({ cat: id, sub_cat: null });
                    }
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
</style>
@endsection

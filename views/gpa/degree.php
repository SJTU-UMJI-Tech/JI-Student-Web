<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">
    <div class="alert alert-warning">
        The Degree Progress Check Sheet is simple and naive now!
        I will improve the AI later.
    </div>
    
    <div class="row">
        <div class="col-md-4 col-xs-12">
            
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Course:</label>
                    <div class="col-sm-9">
                        <!--<select class="degree-select2 form-control">-->
                        <!--    <option></option>-->
                        <!--    <option value="Bahamas">Bahamas</option>-->
                        <!--    <option value="Bahrain">Bahrain</option>-->
                        <!--    <option value="Bangladesh">Bangladesh</option>-->
                        <!--    <option value="Barbados">Barbados</option>-->
                        <!--    <option value="Belarus">Belarus</option>-->
                        <!--    <option value="Belgium">Belgium</option>-->
                        <!--    <option value="Belize">Belize</option>-->
                        <!--    <option value="Benin">Benin</option>-->
                        <!--</select>-->
                        <select data-placeholder="Choose a Country..." class="chosen-select form-control"  tabindex="2">
                            <option value="">Select</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Aland Islands">Aland Islands</option>
                            <option value="Albania">Albania</option>
                            <option value="Algeria">Algeria</option>
                            <option value="American Samoa">American Samoa</option>
                            <option value="Andorra">Andorra</option>
                            <option value="Angola">Angola</option>
                            <option value="Anguilla">Anguilla</option>
                            <option value="Antarctica">Antarctica</option>
                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Armenia">Armenia</option>
                            <option value="Aruba">Aruba</option>
                            <option value="Australia">Australia</option>
                            <option value="Austria">Austria</option>
                            <option value="Azerbaijan">Azerbaijan</option>
                            <option value="Bahamas">Bahamas</option>
                            <option value="Bahrain">Bahrain</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Barbados">Barbados</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Belgium">Belgium</option>
                            <option value="Belize">Belize</option>
                            <option value="Benin">Benin</option>
                            <option value="Bermuda">Bermuda</option>
                            <option value="Bhutan">Bhutan</option>
                            <option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
                            <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                            <option value="Botswana">Botswana</option>
                            <option value="Bouvet Island">Bouvet Island</option>
                            <option value="Brazil">Brazil</option>
                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                            <option value="Brunei Darussalam">Brunei Darussalam</option>
                            <option value="Bulgaria">Bulgaria</option>
                            <option value="Burkina Faso">Burkina Faso</option>
                            <option value="Burundi">Burundi</option>
                            <option value="Cambodia">Cambodia</option>
                            <option value="Cameroon">Cameroon</option>
                            <option value="Canada">Canada</option>
                            <option value="Cape Verde">Cape Verde</option>
                            <option value="Cayman Islands">Cayman Islands</option>
                            <option value="Central African Republic">Central African Republic</option>
                            <option value="Chad">Chad</option>
                            <option value="Chile">Chile</option>
                            <option value="China">China</option>
                            <option value="Christmas Island">Christmas Island</option>
                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Comoros">Comoros</option>
                            <option value="Congo">Congo</option>
                            <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                            <option value="Cook Islands">Cook Islands</option>
                            <option value="Costa Rica">Costa Rica</option>
                            <option value="Cote D'ivoire">Cote D'ivoire</option>
                            <option value="Croatia">Croatia</option>
                            <option value="Cuba">Cuba</option>
                            <option value="Curacao">Curacao</option>
                            <option value="Cyprus">Cyprus</option>
                            <option value="Czech Republic">Czech Republic</option>
                            <option value="Denmark">Denmark</option>
                            <option value="Djibouti">Djibouti</option>
                            <option value="Dominica">Dominica</option>
                            <option value="Dominican Republic">Dominican Republic</option>
                            <option value="Ecuador">Ecuador</option>
                            <option value="Egypt">Egypt</option>
                            <option value="El Salvador">El Salvador</option>
                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                            <option value="Eritrea">Eritrea</option>
                            <option value="Estonia">Estonia</option>
                            <option value="Ethiopia">Ethiopia</option>
                            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                            <option value="Faroe Islands">Faroe Islands</option>
                            <option value="Fiji">Fiji</option>
                            <option value="Finland">Finland</option>
                            <option value="France">France</option>
                            <option value="French Guiana">French Guiana</option>
                            <option value="French Polynesia">French Polynesia</option>
                            <option value="French Southern Territories">French Southern Territories</option>
                            <option value="Gabon">Gabon</option>
                            <option value="Gambia">Gambia</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Germany">Germany</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Gibraltar">Gibraltar</option>
                            <option value="Greece">Greece</option>
                            <option value="Greenland">Greenland</option>
                            <option value="Grenada">Grenada</option>
                            <option value="Guadeloupe">Guadeloupe</option>
                            <option value="Guam">Guam</option>
                            <option value="Guatemala">Guatemala</option>
                            <option value="Guernsey">Guernsey</option>
                            <option value="Guinea">Guinea</option>
                            <option value="Guinea-bissau">Guinea-bissau</option>
                            <option value="Guyana">Guyana</option>
                            <option value="Haiti">Haiti</option>
                            <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                            <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                            <option value="Honduras">Honduras</option>
                            <option value="Hong Kong">Hong Kong</option>
                            <option value="Hungary">Hungary</option>
                            <option value="Iceland">Iceland</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                            <option value="Iraq">Iraq</option>
                            <option value="Ireland">Ireland</option>
                            <option value="Isle of Man">Isle of Man</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>
                            <option value="Jamaica">Jamaica</option>
                            <option value="Japan">Japan</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Jordan">Jordan</option>
                            <option value="Kazakhstan">Kazakhstan</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Kiribati">Kiribati</option>
                            <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                            <option value="Korea, Republic of">Korea, Republic of</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                            <option value="Latvia">Latvia</option>
                            <option value="Lebanon">Lebanon</option>
                            <option value="Lesotho">Lesotho</option>
                            <option value="Liberia">Liberia</option>
                            <option value="Libya">Libya</option>
                            <option value="Liechtenstein">Liechtenstein</option>
                            <option value="Lithuania">Lithuania</option>
                            <option value="Luxembourg">Luxembourg</option>
                            <option value="Macao">Macao</option>
                            <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                            <option value="Madagascar">Madagascar</option>
                            <option value="Malawi">Malawi</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Maldives">Maldives</option>
                            <option value="Mali">Mali</option>
                            <option value="Malta">Malta</option>
                            <option value="Marshall Islands">Marshall Islands</option>
                            <option value="Martinique">Martinique</option>
                            <option value="Mauritania">Mauritania</option>
                            <option value="Mauritius">Mauritius</option>
                            <option value="Mayotte">Mayotte</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                            <option value="Moldova, Republic of">Moldova, Republic of</option>
                            <option value="Monaco">Monaco</option>
                            <option value="Mongolia">Mongolia</option>
                            <option value="Montenegro">Montenegro</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Morocco">Morocco</option>
                            <option value="Mozambique">Mozambique</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="Namibia">Namibia</option>
                            <option value="Nauru">Nauru</option>
                            <option value="Nepal">Nepal</option>
                            <option value="Netherlands">Netherlands</option>
                            <option value="New Caledonia">New Caledonia</option>
                            <option value="New Zealand">New Zealand</option>
                            <option value="Nicaragua">Nicaragua</option>
                            <option value="Niger">Niger</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Niue">Niue</option>
                            <option value="Norfolk Island">Norfolk Island</option>
                            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                            <option value="Norway">Norway</option>
                            <option value="Oman">Oman</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Palau">Palau</option>
                            <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                            <option value="Panama">Panama</option>
                            <option value="Papua New Guinea">Papua New Guinea</option>
                            <option value="Paraguay">Paraguay</option>
                            <option value="Peru">Peru</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Pitcairn">Pitcairn</option>
                            <option value="Poland">Poland</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Puerto Rico">Puerto Rico</option>
                            <option value="Qatar">Qatar</option>
                            <option value="Reunion">Reunion</option>
                            <option value="Romania">Romania</option>
                            <option value="Russian Federation">Russian Federation</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Saint Barthelemy">Saint Barthelemy</option>
                            <option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                            <option value="Saint Lucia">Saint Lucia</option>
                            <option value="Saint Martin (French part)">Saint Martin (French part)</option>
                            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                            <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                            <option value="Samoa">Samoa</option>
                            <option value="San Marino">San Marino</option>
                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Senegal">Senegal</option>
                            <option value="Serbia">Serbia</option>
                            <option value="Seychelles">Seychelles</option>
                            <option value="Sierra Leone">Sierra Leone</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                            <option value="Slovakia">Slovakia</option>
                            <option value="Slovenia">Slovenia</option>
                            <option value="Solomon Islands">Solomon Islands</option>
                            <option value="Somalia">Somalia</option>
                            <option value="South Africa">South Africa</option>
                            <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                            <option value="South Sudan">South Sudan</option>
                            <option value="Spain">Spain</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sudan">Sudan</option>
                            <option value="Suriname">Suriname</option>
                            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                            <option value="Swaziland">Swaziland</option>
                            <option value="Sweden">Sweden</option>
                            <option value="Switzerland">Switzerland</option>
                            <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                            <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                            <option value="Tajikistan">Tajikistan</option>
                            <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Timor-leste">Timor-leste</option>
                            <option value="Togo">Togo</option>
                            <option value="Tokelau">Tokelau</option>
                            <option value="Tonga">Tonga</option>
                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                            <option value="Tunisia">Tunisia</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Turkmenistan">Turkmenistan</option>
                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                            <option value="Tuvalu">Tuvalu</option>
                            <option value="Uganda">Uganda</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                            <option value="Uruguay">Uruguay</option>
                            <option value="Uzbekistan">Uzbekistan</option>
                            <option value="Vanuatu">Vanuatu</option>
                            <option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
                            <option value="Viet Nam">Viet Nam</option>
                            <option value="Virgin Islands, British">Virgin Islands, British</option>
                            <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                            <option value="Wallis and Futuna">Wallis and Futuna</option>
                            <option value="Western Sahara">Western Sahara</option>
                            <option value="Yemen">Yemen</option>
                            <option value="Zambia">Zambia</option>
                            <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Credit:</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="Email" class="form-control">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-xs-12">
            123
        </div>
    </div>


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/degree.hbs'; ?>

<script type="text/javascript">
    
    require(['jquery', 'handlebars', 'footable', 'chosen'], function ($, Handlebars) {
        $(document).ready(function () {
            var i;
            var major = 'ECE';
            var dd = false;
            
            // Process the student's scores
            <?php /** @var $score string */?>
            var score = [{course_id: true, grade: true}];
            score = <?php echo $score;?>;
            var score_list = {};
            for (i = 0; i < score.length; i++) {
                score_list[score[i].course_id] = score[i].grade;
            }
            
            // Read the course list
            var courses = {"course": true, "equivalent": true};
            <?php /** @var $courses string */?>
            courses = <?php echo $courses;?>;
            
            // Generate the Table Data shown in the page
            var table_data = {};
            var degree = courses.degree[major];
            if (!degree.hasOwnProperty('credit-dd') || !dd) degree['credit-dd'] = {};
            for (i in degree.credit) {
                if (degree.credit.hasOwnProperty(i)) {
                    if (degree['credit-dd'].hasOwnProperty(i)) {
                        degree.credit[i] = degree['credit-dd'][i];
                    }
                    if (degree.credit[i] > 0 && courses.category.hasOwnProperty(i)) {
                        table_data[i] = {
                            title: courses.category[i],
                            course_list: [],
                            credit: degree.credit[i],
                            credit_now: 0,
                            credit_shift: 0,
                            credit_display: 0,
                            gpa: 0
                        };
                    }
                }
            }
            
            function setGrade(data, course, id) {
                course.id = id;
                course.score = 0;
                if (score_list.hasOwnProperty(id)) {
                    if (courses.letter.hasOwnProperty(score_list[id])) {
                        course.grade = courses.letter[score_list[id]];
                        course.score = score_list[id];
                        data.credit_now += course.credit;
                        data.credit_grade += course.credit * Math.min(40, score_list[id]);
                    }
                }
                data.course_list.push(course);
                delete score_list[id];
            }
            
            // Process courses in "EF", "PS", "SJTU" (not elective)
            var cat_temp = ["EF", "PS", "SJTU"];
            for (i in courses.course) {
                if (courses.course.hasOwnProperty(i)) {
                    var course = courses.course[i];
                    if (cat_temp.indexOf(course.category) >= 0 &&
                        course.degree.indexOf(major) >= 0) {
                        //table_data[course.category].courses[i] = course;
                        setGrade(table_data[course.category], course, i);
                    }
                }
            }
            
            // Remove Equivalent EF courses
            var course_list = table_data["EF"].course_list;
            /*var course_map = {};
             for (i = 0; i < course_list.length; i++) {
             course_map[course_list[i].id] = course_list[i];
             }*/
            for (i = 0; i < course_list.length; i++) {
                course = course_list[i];
                if (course.hasOwnProperty('equivalent')) {
                    var flag = true;
                    if (course.hasOwnProperty('grade')) flag = false;
                    else {
                        var name = course.name;
                        var id = course.id;
                    }
                    for (var j = 0; j < course.equivalent.length; j++) {
                        for (var k = 0; k < course_list.length; k++) {
                            if (course.equivalent[j] === course_list[k].id)break;
                        }
                        if (k === course_list.length)continue;
                        var course_eq = course_list[k];
                        if (!flag) course_list.splice(k, 1);
                        else if (course_eq.hasOwnProperty('grade')) flag = false;
                        else {
                            name += '/' + course_eq.name;
                            id += '/' + course_eq.id;
                            course_list.splice(k, 1);
                        }
                    }
                    if (flag) {
                        course.name = name;
                        course.id = id;
                    } else if (!course.hasOwnProperty('grade')) {
                        for (k = 0; k < course_list.length; k++) {
                            if (course.id === course_list[k].id)break;
                        }
                        if (k < course_list.length) course_list.splice(k, 1);
                    }
                }
            }
            
            
            window.console.log(table_data);
            
            // Process elective courses
            function addSpecial(_major, id, category) {
                if (major === _major && score_list.hasOwnProperty(id)) {
                    setGrade(table_data[category], courses.course[id], id);
                }
            }
            
            addSpecial("ECE", "VG496", "IB");
            addSpecial("ME", "VE401", "AM");
            addSpecial("ME", "VE301", "AM");
            for (i in score_list) {
                if (score_list.hasOwnProperty(i) && courses.course.hasOwnProperty(i)) {
                    course = courses.course[i];
                    setGrade(table_data[course.category], course, i);
                }
            }
            
            if (major === "ECE") {
                cat_temp = ["CE", "UTE", "FTE", "GE"];
            } else if (major === "ME") {
                cat_temp = ["AM", "FTE", "GE"];
            }
            for (i = 0; i < cat_temp.length - 1; i++) {
                var credit = table_data[cat_temp[i]].credit - table_data[cat_temp[i]].credit_shift;
                var credit_now = table_data[cat_temp[i]].credit_now;
                flag = true;
                while (table_data[cat_temp[i]].course_list.length > 0) {
                    course = table_data[cat_temp[i]].course_list.pop();
                    credit_now -= course.credit;
                    if (credit_now >= credit) {
                        table_data[cat_temp[i + 1]].course_list.unshift(course);
                        table_data[cat_temp[i + 1]].credit_now += course.credit;
                    } else {
                        flag = false;
                        table_data[cat_temp[i]].course_list.push(course);
                        break;
                    }
                }
                if (!flag) credit_now += course.credit;
                table_data[cat_temp[i]].credit_now = credit_now;
                if (credit_now > credit) {
                    table_data[cat_temp[i + 1]].credit_shift += credit_now - credit;
                }
            }
            
            // Calculate GPA
            for (i in table_data) {
                if (table_data.hasOwnProperty(i)) {
                    credit = table_data[i].credit_now + table_data[i].credit_shift;
                    table_data[i].credit_display = Math.min(credit, table_data[i].credit);
                    var credit_score = 0;
                    for (j = 0; j < table_data[i].course_list.length; j++) {
                        course = table_data[i].course_list[j];
                        credit_score += course.credit * Math.min(40, course.score);
                    }
                    table_data[i].gpa = table_data[i].credit_now > 0 ?
                        credit_score / 10. / table_data[i].credit_now : 0;
                    table_data[i].gpa = Number(table_data[i].gpa).toFixed(3);
                }
            }
            
            
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('degree', $("#degree-template").html());
            
            var config = {
                "id": "degree",
                "title": "Degree Process Check Sheet",
                "tools": [
                    {"collapse": true},
                    {"edit": true},
                    {"close": true}
                ],
                "body": [{
                    "template": "degree",
                    "data": table_data
                }]
            };
            $("#body-wrapper").append(template(config));
            
            $("#degree").find("a.edit-link").on('click', function () {
                alert(1)
            });
            
            /*$(".degree-select2").select2({
                placeholder: "Select a course",
                allowClear: true
            });*/
            $('.chosen-select').chosen({width: "100%"});
    
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


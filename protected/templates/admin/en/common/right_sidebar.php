<?php /*
 <div id="rightsidebar" class="right_sidebar">
        <div class="setting_div">
            <a href="#" class="settingbar"><i class="fa fa-gear fa-spin"></i></a>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Settings" aria-expanded="true">Settings</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contact" aria-expanded="false">Contact</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#activity" aria-expanded="false">Activity</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane vivify fadeIn active" id="Settings" aria-expanded="true">
                <div class="sidebar-scroll">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Color Skins</h3>
                        </div>
                        <div class="card-body">
                            <ul class="choose-skin list-unstyled">
                                <li data-theme="black"><div class="black"></div></li>
                                <li data-theme="azure"><div class="azure"></div></li>
                                <li data-theme="indigo"><div class="indigo"></div></li>
                                <li data-theme="purple"><div class="purple"></div></li>
                                <li data-theme="orange"><div class="orange"></div></li>
                                <li data-theme="green"><div class="green"></div></li>
                                <li data-theme="cyan" class="active"><div class="cyan"></div></li>
                                <li data-theme="blush"><div class="blush"></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">General Setting</h3>
                        </div>
                        <div class="card-body">
                            <ul class="setting-list list-unstyled">
                                <li>
                                    <label class="custom-switch">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Report Panel Usage</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="custom-switch">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Email Redirect</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="custom-switch">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Notifications</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="custom-switch">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Auto Updates</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="custom-switch">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Offline</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="custom-switch">
                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Location Permission</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Storage</h3>
                        </div>
                        <div class="card-body">
                            <div class="progress progress-xs mb-0">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 89%;">
                                </div>
                            </div>
                            <small>50MB of 10GB Used</small>
                            <button type="button" class="btn btn-primary btn-block mt-3">Upgrade Storage</button>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane vivify fadeIn" id="contact" aria-expanded="false">
                <div class="sidebar-scroll">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Contact List</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled contact-list">
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar1.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Vincent Porter <span class="d-block">London UK</span></h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar2.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Mike Thomas <span class="d-block">London UK</span></h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar3.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Aiden Chavaz</h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar4.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Vincent Porter <span class="d-block">London UK</span></h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar5.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Mike Thomas <span class="d-block">London UK</span></h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar6.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Aiden Chavaz</h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar7.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Mike Thomas <span class="d-block">London UK</span></h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="contact-img">
                                        <img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar8.jpg" class="rounded" alt="">
                                    </span>
                                    <h4 class="contact-name">Aiden Chavaz</h4>
                                    <div class="action">
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-skype"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope-o"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane vivify fadeIn" id="activity" aria-expanded="false">
                <div class="sidebar-scroll">
                    <div class="card">
                        <div class="card-body">
                            <ul class="new_timeline mt-3">
                                <li>
                                    <div class="bullet pink"></div>
                                    <div class="time">11:00am</div>
                                    <div class="desc">
                                        <h3>Attendance</h3>
                                        <h4>Computer Class</h4>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet pink"></div>
                                    <div class="time">11:30am</div>
                                    <div class="desc">
                                        <h3>Added an interest</h3>
                                        <h4>“Volunteer Activities”</h4>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet green"></div>
                                    <div class="time">12:00pm</div>
                                    <div class="desc">
                                        <h3>Developer Team</h3>
                                        <h4>Hangouts</h4>
                                        <ul class="list-unstyled team-info margin-0 p-t-5">
                                            <li><img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar1.jpg" alt="Avatar"></li>
                                            <li><img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar2.jpg" alt="Avatar"></li>
                                            <li><img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar3.jpg" alt="Avatar"></li>
                                            <li><img src="<?php echo SITE_DIR; ?>assets/theme/assets/images/xs/avatar4.jpg" alt="Avatar"></li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet green"></div>
                                    <div class="time">2:00pm</div>
                                    <div class="desc">
                                        <h3>Responded to need</h3>
                                        <a href="#">“In-Kind Opportunity”</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet orange"></div>
                                    <div class="time">1:30pm</div>
                                    <div class="desc">
                                        <h3>Lunch Break</h3>
                                    </div>
                                </li>
                                <li>
                                    <div class="bullet green"></div>
                                    <div class="time">2:38pm</div>
                                    <div class="desc">
                                        <h3>Finish</h3>
                                        <h4>Go to Home</h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 */ ?>
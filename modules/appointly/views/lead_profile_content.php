<?php if (isset($td_appointments) && ! empty($td_appointments)) : ?>
            <div class="">
                <div class="">
                    <div class="">
                        <div class="">
                            <!-- <span class="label label-info label-big pull-right mtop5"><?= _d(date('Y-m-d')); ?></span> -->
                            <h4><?= _l('appointment_lead_tab'); ?>
                            </h4>
                            <hr class="mbot0">
                            <?php foreach ($td_appointments as $appointment) : ?>
                                <div class="todays_appointment col-2 mleft20 appointly-secondary pull-left mtop10">
                                    <h3 class="text-muted mtop1">
                                        <a href="<?= admin_url('appointly/appointments/view?appointment_id=' . $appointment['id']); ?>"><?= $appointment['subject']; ?></a>
                                    </h3>
                                    <span class="text-muted span_limited">
                                        <?= _l('appointment_description'); ?>
                                        <?= $appointment['description']; ?>
                                    </span>
                                    <h5 class="no-margin">
                                        <span class="text-warning"><?= _l('appointment_scheduled_at'); ?>
                                        </span>
                                        <?= date("H:i A", strtotime($appointment['start_hour'])); ?>
                                    </h5>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="">
                <div class="">
                    <div class="">
                        <div class="">
                            <!-- <span class="label label-info label-big pull-right mtop5"><?= _d(date('Y-m-d')); ?></span> -->
                            <h4><?= _l('appointment_lead_no_appointments'); ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
<?php

use App\Models\User;
use App\Services\OtpService;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required', message: 'No OTP was sent to verify')]
    #[Validate('digits:6', message: 'OTP must contain 6 digits')]
    #[Validate('numeric', message: 'OTP must only contain numeric characters')]
    public string $otp;

    /**
     * @return array|true[]
     */
    public function verifyOtp(): array
    {
        $this->validate();
        $user = auth()->user();

        $service = new OtpService();
        $otp = $service->getRepository()->getValidOtp($user, $this->otp);

        if (!$otp) {
            $possibleOtp = $service->findOtpByCode($this->otp);
            $returnBag = [];

            if ($possibleOtp?->isExpired()) {
                $returnBag['expired'] = true;
                $returnBag['message'] = 'The OTP you provided is expired. Please try again with a new one.';
            } else if ($possibleOtp?->isUsed()) {
                $returnBag['used'] = true;
                $returnBag['message'] = 'OTP has been used. Please try again with a new one.';
            } else {
                $returnBag['no_otp'] = true;
                $returnBag['message'] = 'Cannot verify OTP. Please make sure you entered the right combination.';
            }

            return [
                'success' => false,
                ...$returnBag
            ];
        }

        $otp->verified_at = now();
        $otp->save();

        return ['success' => true];
    }
}; ?>

<div
    x-data="data"
    class="h-screen flex justify-center items-center bg-gray-100"
    @paste="handlePaste($event)"
>
    <div class="!min-w-[600px] w-[80%] h-[30%] bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Card Header -->
        <div class="p-6 text-center">
            <h2 class="text-2xl font-semibold text-gray-800">Please Enter Your OTP</h2>
            <p class="text-gray-600 mt-2">Please check your phone or email for any OTP.</p>
        </div>

        <!-- Card Content -->
        <div class="p-6 ">
            <div class="flex space-x-2 justify-center">
                <template
                    x-for="(input, index) in inputs"
                    :key="index"
                >
                    <input
                        x-model="inputs[index]"
                        :disabled="verifying"
                        autofocus
                        type="text"
                        maxlength="1"
                        min="0"
                        max="9"
                        pattern="\d*"
                        :id="`input-${index}`"
                        class="otp-input"
                        @keyup="valueEntered($event, index)"
                        @keyup.backspace="removeInput(index)"
                    />
                </template>
            </div>

            @if ($errors->any())
                <div class="flex space-x-2 justify-center mt-5">
                <span class="font-bold notification-error">
                    {{ $errors->first() }}
                </span>
                </div>
            @endif


            <template x-if="notificationMessage">
                <div class="flex space-x-2 justify-center mt-5">
                    <span
                        x-text="notificationMessage"
                        class="font-bold"
                        :class="notificationType === 'success' ? 'notification-success' : 'notification-error'"
                    ></span>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function data() {
        return {
            inputs: ['', '', '', '', '', ''],
            notificationMessage: '',
            notificationType: 'success',
            verifying: false,

            reset() {
                return ['', '', '', '', '', ''];
            },

            valueEntered($event, index) {
                if ([37, 39].includes($event.keyCode)) {
                    this.handleArrowInput($event, index);
                    return;
                }

                const regex = /^-?\d+$/;
                if (!regex.test(this.inputs[index])) {
                    this.inputs[index] = '';
                    return;
                }

                if ([46, 8, 86, 17].includes($event.keyCode)) return;

                if (index !== 5) {
                    document.getElementById(`input-${index + 1}`).focus();
                }

                if (index === 5) {
                    this.verifyOtpMaybe();
                }
            },

            handleArrowInput($event, index) {
                if ($event.keyCode === 37) {
                    document.getElementById(`input-${index === 0 ? 5 : index - 1}`).focus();
                }

                if ($event.keyCode === 39) {
                    document.getElementById(`input-${index === 5 ? 0 : index + 1}`).focus();
                }
            },

            removeInput(index) {
                this.inputs[index] = '';

                if (index) {
                    document.getElementById(`input-${index - 1}`).focus();
                }
            },

            handlePaste(event) {
                const pastedValue = event.clipboardData.getData('text');

                if (pastedValue) {
                    if (isNaN(pastedValue)) {
                        this.inputs = this.reset();
                        this.notify(`Invalid OTP '${pastedValue}'`, 'error');
                        return;
                    }

                    setTimeout(() => {
                        this.inputs = this.reset();

                        // Split the pasted value and make sure that we only get the first 6-characters if the user pasted more than 6 digits
                        const values = pastedValue.split('').slice(0, 6);

                        values.forEach((value, index) => {
                            this.inputs[index] = value;
                        });

                        this.verifyOtpMaybe();
                    }, 100);
                }
            },

            verifyOtpMaybe() {
                // integer only tester
                const regex = /^-?\d+$/;

                // verify if all the entered values in the fields is not an empty string and a valid
                if (this.inputs.every((digit) => regex.test(digit))) {
                    this.submitOtp()
                } else {
                    this.inputs = this.reset();
                    document.getElementById(`input-0`).focus();
                }
            },

            async submitOtp() {
                this.verifying = true;

            @this.set('otp', this.inputs.join(''));
            let response = await @this.call('verifyOtp');

                if (response.success) {
                    this.notify('OTP verified successfully', 'success');
                    this.inputs = this.reset();
                    document.getElementById(`input-0`).focus();
                } else {
                    this.notify(response.message, 'error');
                    this.inputs = this.reset();
                    document.getElementById(`input-0`).focus();
                }

                this.verifying = false;
            },

            notify(message, type) {
                this.notificationMessage = message;
                this.notificationType = type;

                setTimeout(() => {
                    this.notificationMessage = '';
                    this.notificationType = 'success';
                }, 5000);
            }
        }
    }
</script>



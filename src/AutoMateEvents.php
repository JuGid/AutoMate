<?php 

namespace Automate;

class AutoMateEvents {
    const RUNNER_SIMPLE_BEGIN = 'core:runner:simple:begin';
    const RUNNER_SIMPLE_END = 'core:runner:simple:end';

    const RUNNER_SPEC_BEGIN = 'core:runner:spec:begin';
    const RUNNER_SPEC_END = 'core:runner:spec:end';

    const RUNNER_ERROR = 'core:runner:error';
    const RUNNER_WIN = 'core:runner:win';

    const RUNNER_ENDS_ERROR = 'core:runner:end:error';

    const STEP_TRANSFORM = 'core:step:transform';
}
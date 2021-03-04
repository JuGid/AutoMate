<?php 

namespace Automate;

class AutoMateEvents {
    const RUNNER_BEGIN = 'core:runner:begin';
    const RUNNER_END = 'core:runner:end';
    const RUNNER_ERROR = 'core:runner:error';
    const RUNNER_WIN = 'core:runner:win';
    const STEP_TRANSFORM = 'core:step:transform';
}
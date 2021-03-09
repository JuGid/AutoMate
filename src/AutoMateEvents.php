<?php

namespace Automate;

class AutoMateEvents
{
    public const RUNNER_SIMPLE_BEGIN = 'core:runner:simple:begin';
    public const RUNNER_SIMPLE_END = 'core:runner:simple:end';
    public const RUNNER_SPEC_BEGIN = 'core:runner:spec:begin';
    public const RUNNER_SPEC_END = 'core:runner:spec:end';
    public const RUNNER_ERROR = 'core:runner:error';
    public const RUNNER_WIN = 'core:runner:win';
    public const RUNNER_ENDS_ERROR = 'core:runner:end:error';
    public const STEP_TRANSFORM = 'core:step:transform';
}

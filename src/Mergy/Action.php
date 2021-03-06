<?php
/**
 * mergy
 *
 * Copyright (c)2011-2012, Hans-Peter Buniat <hpbuniat@googlemail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in
 * the documentation and/or other materials provided with the
 * distribution.
 *
 * * Neither the name of Hans-Peter Buniat nor the names of his
 * contributors may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package mergy
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2012 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Mergy;


/**
 * Mediator to create actions and add them to a handler
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2012 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/mergy
 */
class Action {

    /**
     * The Revisions to handle
     *
     * @var array <\Mergy\Revision>
     */
    protected $_aRevisions = array();

    /**
     * The Action-Handler
     *
     * @var Action\Handler
     */
    protected $_oActionHandler;

    /**
     * The global configuration
     *
     * @var \stdClass
     */
    protected $_oConfig;

    /**
     * The notifier wrapper
     *
     * @var \notifyy\Collection
     */
    protected $_oNotifier;

    /**
     * Step-Order
     *
     * @var array
     */
    protected $_aSteps = array(
        'init',
        'pre',
        'merge',
        'post'
    );

    /**
     * Should the processing continue after a step?
     *
     * @var boolean
     */
    protected $_bProcess = true;

    /**
     * Create the action-mediator
     *
     * @param  \stdclass $oConfig
     * @param  \notifyy\Collection $oNotifier
     */
    public function __construct(\stdclass $oConfig, \notifyy\Collection $oNotifier) {
        $this->_oActionHandler = new Action\Handler($oConfig, $oNotifier);
        $this->_oConfig = $oConfig;
        $this->_oNotifier = $oNotifier;
        if (isset($oConfig->mergeRevisions) === true) {
            $this->_aRevisions = $oConfig->mergeRevisions;
        }
    }

    /**
     * Special method to execute a single action by name
     *
     * @param  string $sCommand
     *
     * @return string
     */
    public function command($sCommand) {
        $oActionBuilder = new Action\Builder($this->_oActionHandler, $this->_oConfig, $this->_oNotifier);
        $oActionBuilder->build($sCommand, new \stdClass());

        $sReturn = $this->_oActionHandler->{Action\Handler::SINGLE}()->get($sCommand);
        $this->_oActionHandler->reset();
        unset($oActionBuilder);

        return $sReturn;
    }

    /**
     * Setup the action-handler, including the steps
     *
     * @return Action
     */
    public function setup() {
        $oActionBuilder = new Action\Builder($this->_oActionHandler, $this->_oConfig, $this->_oNotifier);
        foreach ($this->_aSteps as $sStep) {
            if (isset($this->_oConfig->$sStep) and $this->_oConfig->$sStep instanceof \stdClass) {
                foreach ($this->_oConfig->$sStep as $sType => $oEntry) {
                    $oActionBuilder->build($sType, $oEntry, $sStep);
                }
            }
        }

        return $this;
    }

    /**
     * Execute a step
     *
     * @param  string $sMethod
     * @param  array $aArgs
     *
     * @return Action
     */
    public function __call($sMethod, $aArgs) {
        if (in_array($sMethod, $this->_aSteps) === true and $this->_bProcess === true) {
            if (defined('VERBOSE') === true and VERBOSE === true) {
                \Mergy\TextUI\Output::info('Handling ' . $sMethod . '-Stack');
            }

            try {
                $this->_oActionHandler->$sMethod($this->_aRevisions);
            }
            catch (Exception $e) {
                $this->_bProcess = false;
                \Mergy\TextUI\Output::error($e->getMessage());
            }
        }

        return $this;
    }
}
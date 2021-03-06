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

namespace Mergy\Revision;

/**
 * Class for creating a revision
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2012 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/mergy
 */
class Builder {

    /**
     * The Subversion-VCS
     *
     * @var string
     */
    const SUBVERSION = 'Subversion';

    /**
     * Aggregator for differences of a revision
     *
     * @var string
     */
    const AGGREGATE_DIFF = 'Diff';

    /**
     * Aggregator for modified/added/etc files
     *
     * @var string
     */
    const AGGREGATE_FILES = 'Files';

    /**
     * Aggregator for revision details (author, comment, date)
     *
     * @var string
     */
    const AGGREGATE_INFO = 'Info';

    /**
     * The command-class to pass through
     *
     * @var \Mergy\Util\Command
     */
    protected $_oCommand = null;

    /**
     * The vcs of the project
     *
     * @var string
     */
    protected $_sVersionControl = '';

    /**
     * Create the builder with a specific vcs
     *
     * @param  string $sVersionControl
     * @param  \Mergy\Util\Command $oCommand
     */
    public function __construct($sVersionControl, \Mergy\Util\Command $oCommand) {
        $this->_sVersionControl = $sVersionControl;
        $this->_oCommand = $oCommand;
    }

    /**
     * Create a revision
     *
     * @param  string $sRepository
     * @param  int $iRevision
     *
     * @return \Mergy\Revision
     */
    public function build($sRepository, $iRevision) {
        return new \Mergy\Revision($sRepository, $iRevision, $this);
    }

    /**
     * Get a detail aggregator
     *
     * @param  string $sAggregator
     * @param  array $aParameter
     *
     * @return AggregatorAbstract
     */
    public function getAggregator($sAggregator, array $aParameter) {
        $sClass = sprintf('\\Mergy\\Revision\\%s\\%s', $this->_sVersionControl, $sAggregator);

        $oReflection = new \ReflectionClass($sClass);
        $oAggregator = $oReflection->newInstanceArgs($aParameter);
        return $oAggregator->setCommand($this->_oCommand);
    }
}

/*
 * Copyright (C) 2024 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

'use strict';

fundingModule.factory('fundingClearingProcessService', ['crmApi4', function(crmApi4) {
  function getOptionLabels(id, field) {
    return crmApi4('FundingClearingProcess', 'getFields', {
      loadOptions: true,
      values: {id},
      where: [['name', '=', field]],
      select: ['options']
    }).then((result) => result[0] ? result[0].options || {} : {});
  }

  /**
   * @param {string} field
   *
   * @returns {Promise<object[]>}
   *   Options with option name as index.
   */
  function getOptions(field) {
    return crmApi4('FundingClearingProcess', 'getFields', {
      loadOptions: [
        'id',
        'name',
        'label',
        'abbr',
        'description',
        'color',
        'icon',
      ],
      where: [['name', '=', field]],
      select: ['options']
    }).then(function (result) {
      const options = result[0].options || [];
      const optionsByName = {};
      options.forEach((option) => {
        optionsByName[option.name] = option;
      });

      return optionsByName;
    });
  }

  return {
    /**
     * @param {array<integer>} ids
     * @param {string} action
     * @returns {Promise}
     */
    applyActionMultiple: (ids, action) => crmApi4('FundingClearingProcess', 'applyActionMultiple', {ids, action}),

    get: (id) => crmApi4('FundingClearingProcess', 'get', {
      where: [['id', '=', id]],
    }).then(function (result) {
      return result[0] || null;
    }),

    /**
     * @param {array<integer>} ids
     * @returns {Promise}
     */
    getAllowedActionsMultiple: (ids) => crmApi4('FundingClearingProcess', 'getAllowedActionsMultiple', {ids}),

    /**
     * @param {integer} applicationProcessId
     * @returns {Promise}
     */
    getByApplicationProcessId: (applicationProcessId) => crmApi4('FundingClearingProcess', 'get', {
      where: [['application_process_id', '=', applicationProcessId]],
    }),
    getFormData: (id) => crmApi4('FundingClearingProcess', 'getFormData', {id}).then(function (result) {
      return result.data || null;
    }),

    getForm: (id) => crmApi4('FundingClearingProcess', 'getForm', {id}),
    submitForm: (id, data) => crmApi4('FundingClearingProcess', 'submitForm', {id, data}),
    validateForm: (id, data) => crmApi4('FundingClearingProcess', 'validateForm', {id, data}),

    /**
     * @param {integer} clearingProcessId
     * @param {integer} reviewerContactId
     * @returns {Promise}
     */
    setCalculativeReviewer: (clearingProcessId, reviewerContactId) =>
      crmApi4('FundingClearingProcess', 'setCalculativeReviewer', {clearingProcessId, reviewerContactId}),

    /**
     * @param {integer} clearingProcessId
     * @param {integer} reviewerContactId
     * @returns {Promise}
     */
    setContentReviewer: (clearingProcessId, reviewerContactId) =>
      crmApi4('FundingClearingProcess', 'setContentReviewer', {clearingProcessId, reviewerContactId}),

    /**
     * @returns {Promise<object[]>}
     *   Options with option name as index.
     */
    getStatusOptions: () => getOptions('status'),
    getOptionLabels: (id, field) => getOptionLabels(id, field),
  };
}]);

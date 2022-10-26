/*
 * Copyright (C) 2022 SYSTOPIA GmbH
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

fundingModule.factory('recipientRelationService', ['crmApi4', function(crmApi4) {
  return {
    getAll: (fundingProgramId) => crmApi4('FundingRecipientContactRelation', 'get',
        {where:[['funding_program_id', '=', fundingProgramId]]}),
    replaceAll: (fundingProgramId, relations) => crmApi4('FundingRecipientContactRelation', 'replace',
        {where: [['funding_program_id', '=', fundingProgramId]], records: relations }),
  }
}]);

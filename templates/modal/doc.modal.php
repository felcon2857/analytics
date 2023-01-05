<div class="modal-header">
    <h5 class="modal-title">Select Doctors</h5>
    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button> -->
</div>
<div class="modal-body">
    <div class="table-area">
        <table>
            <thead>
                <tr>
                    <th class="text-left" width="1%"><input type="checkbox"></th>
                    <th class="text-left">Doctors</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in dxObj">
                    <td class="text-left">
                        <input type="checkbox" ng-model="item.doc" ng-true-value="Y" ng-false-value="N">
                    </td>
                    <td class="text-left">{{item.doc}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn-theme" ng-click="closeFilterBox()">Close</button>
    <button type="button" class="btn-theme btn-theme-green" ng-click="insert_checked(dxObj)">Save Filter</button>
</div>
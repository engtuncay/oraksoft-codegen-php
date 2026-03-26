// FiMeta Class Generation  - v0.4 
import { FiMeta } from "../../orak_modules/orak-util-js/orak-util-js.js";

export class FimCdgMssqlOpts {

  static mssqlCreateTable() {
    let fiMeta = FiMeta.create("mssqlCreateTable");
    fiMeta.ftTxValue = "Create Table";
    fiMeta.ftLnKey = 1;

    return fiMeta;
  }

  static mssqlAlterTable() {
    let fiMeta = FiMeta.create("mssqlAlterTable");
    fiMeta.ftTxValue = "Alter Table ";
    fiMeta.ftLnKey = 2;

    return fiMeta;
  }


}

// FiMeta Class Generation (By Dml) - v0.4 
import { FiMeta } from "../../orak_modules/oraksoft-ui/oraksoft-ui.js";

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

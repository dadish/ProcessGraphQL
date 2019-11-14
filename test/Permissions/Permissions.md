Superuser
=========
- Create
  ✅ Available
  - NotAvailable
    ✅ Template legal
    ✅ Required Fields legal
    ✅ Template NoParents On
    - Template ParentTemplates
      ✅ Parent Template legal
      ✅ Parent Template NoChildren On
      ✅ Parent Template ChildTemplates Match
  ✅ Allowed
  - NotAllowed
    ✅ Template OnlyOne On (if already exists)
    ✅ Parent Template legal
    ✅ Parent Template NoChildren On
    ✅ Parent Template ChildTemplates Match
    ✅ Template parentTemplates Match

- View
  - Available
    ✅ Template legal
    ✅ Field legal
  - NotAvailable
    ✅ Template legal
    ✅ Field legal

- Update
  ✅ Available
  - NotAvailable
    ✅ Template legal
  ✅ Allowed
  - NotAllowed
    ✅ Rename
    - Move + Rename
    - Move
      ✅ Name conflict
      ✅ ParentTemplates Match
      ✅ Parent Template legal
      ✅ Parent Template NoChildren On
      ✅ Parent Template ChildTemplates Match

- Trash
  ✅ Available
  ✅ Not Available
  ✅ Allowed
  - NotAllowed
    ✅ Template legal

Editor
======
- Create
  ✅ Available
  - NotAvailable
    ✅ Template legal
    ✅ Template Create Permission
    ✅ Required Fields legal
    ✅ Required Fields Edit Permission
    ✅ Required Fields Context Edit Permission
    ✅ Template NoParents On
    - Template ParentTemplates
      ✅ Parent Template legal
      ✅ Parent Template Add Permission
      ✅ Parent Template NoChildren On
      ✅ Parent Template ChildTemplates Match
  ✅ Allowed
  - NotAllowed
    ✅ Template OnlyOne On (if already exists)
    ✅ Parent Template legal
    ✅ Parent Template Add Permission
    ✅ Parent Template NoChildren On
    ✅ Parent Template childTemplates Match
    ✅ Template parentTemplates Match

- View
  - Available
    ✅ Template legal
    ✅ Field legal
  - NotAvailable
    ✅ Template legal
    ✅ Template View Permission
    ✅ Field legal
    ✅ Field View Permission
    ✅ Field Context View Permission

- Update
  ✅ Available
  - NotAvailable
    ✅ Template legal
    ✅ Template Edit Permission
    ✅ Field legal
    ✅ Field Edit Permission
    ✅ Field Context Edit Permission
  ✅ Allowed
  - NotAllowed
    ✅ Template page-edit-created Permission
    ✅ Rename
    ✅ Move + Rename Conflict
    - Move
      ✅ Name conflict
      ✅ Parent Template legal
      ✅ ParentTemplates Match
      ✅ Template page-move Permission
      ✅ Parent Template page-add Permission
      ✅ Parent Template NoChildren On
      ✅ Parent Template ChildTemplates Match

- Trash
  - Available
    ✅ Template Delete Permission
    ✅ Template page-edit-trash-created Permission
  - Not Available
    ✅ Template legal
    ✅ Template Delete Permission | Template page-edit-trash-created Permission
  - Allowed
    - Delete Permission
    ✅ page-edit-trash-created permission
  - NotAllowed
    ✅ Template legal
    ✅ Template Delete Permission | Template page-edit-trash-created Permission
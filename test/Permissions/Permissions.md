Superuser
=========
- Create
  ✅ Available
  - NotAvailable
    ✅ Template legal
    ✅ Required Fields legal
    ✅ Template NoParent Off
    - Template ParentTemplates
      ✅ Parent Template legal
      ✅ Parent Template NoChildren Off
      ✅ Parent Template ChildTemplates Match
  ✅ Allowed
  - NotAllowed
    ✅ Template OnlyOne Off (if already exists)
    ✅ Parent Template legal
    ✅ Parent Template NoChild Off
    ✅ Parent Template ChildTemplates Match

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
      ✅ Parent Template NoChild Off
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
  - Available
  - NotAvailable
    - Template legal
    - Template Create Permission
    - Required Fields legal
    - Required Fields Edit Permission
    - Required Fields Context Edit Permission
    - Template NoParent Off
    - Template ParentTemplates
      - Parent Template legal
      - Parent Template Add Permission
      - Parent Template NoChild Off
      - Parent Template ChildTemplates Match
  - Allowed
  - NotAllowed
    - Template OnlyOne Off (if already exists)
    - Parent Template legal
    - Parent Template Add Permission
    - Parent Template NoChild Off
    - Parent Template ChildTemplates Match

- View
  - Available
    - Template legal
    - Field legal
  - NotAvailable
    - Template legal
    - Template View Permission
    - Field legal
    - Field View Permission
    - Field Context View Permission

- Update
  - Available
  - NotAvailable
    - Template legal
    - Template Edit Permission
    - Fields Edit Permission
    - Fields Context Edit Permission
  - Allowed
  - NotAllowed
    - Template page-edit-created Permission
    - Rename
      - Name conflict
      - Template page-rename Permission
    - Move + Rename
    - Move
      - NoParents off
      - ParentTemplates Match
      - Template page-move Permission
      - Parent Template legal
      - Parent Template Add Permission
      - Parent Template NoChild Off
      - Parent Template ChildTemplates Match

- Trash
  - Available
    - Template Delete Permission
    - Template page-edit-trash-created Permission
  - Not Available
    - Template legal
    - Template Delete Permission | Template page-edit-trash-created Permission
  - Allowed
  - NotAllowed
    - Template legal
    - Template Delete Permission | Template page-edit-trash-created Permission

- Delete
  - Available
  - Allowed
  - NotAllowed
    - Template legal
    - Template Delete Permission
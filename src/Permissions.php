<?php namespace ProcessWire\GraphQL;

use ProcessWire\Template;
use ProcessWire\Templates;
use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;

class Permissions
{
  public const pageAddPermission = "page-add";
  public const pageCreatePermission = "page-create";
  public const pageDeletePermission = "page-delete";
  public const pageEditPermission = "page-edit";
  public const pageMovePermission = "page-move";
  public const pageViewPermission = "page-view";
  public const pageEditCreatedPermission = "page-edit-created";
  public const pageEditTrashCreatedPermission = "page-edit-trash-created";

  /**
   * Checks if the page using this template can be viewed by the current user.
   *
   * @param Template $template
   * @return boolean
   */
  public static function canView(Template $template)
  {
    $user = Utils::user();

    // can view if superuser
    if ($user->isSuperuser()) {
      return true;
    }

    // can't view if access rules are not defined
    if (!self::definesAccess($template)) {
      return false;
    }

    // can't view if user does not have view permission for the given template
    if (!$user->hasPermission(self::pageViewPermission, $template)) {
      return false;
    }

    return true;
  }

  /**
   * Tells if the current user can create pages using the given template.
   *
   * @param Template $template
   * @return boolean
   */
  public static function canCreate(Template $template)
  {
    $user = Utils::user();

    // can't create a page if no parent allowed
    if ($template->noParents == 1) {
      return false;
    }

    // can't create if allowed parents are not legal
    if (!$template->noParents && count($template->parentTemplates)) {
      // filter out the parents that has noChildren checked or
      // has configured childTemplates without the target template
      $parentTemplates = array_filter($template->parentTemplates, function (
        $templateId
      ) use ($template) {
        $parentTemplate = Utils::templates()->get($templateId);
        if ($parentTemplate->noChildren) {
          return false;
        }
        if (
          count($parentTemplate->childTemplates) &&
          !in_array($template->id, $parentTemplate->childTemplates)
        ) {
          return false;
        }
        return true;
      });

      // get templates that user can add pages to
      $addTemplates = self::getAddTemplates()->explode("id");
      if (!count(array_intersect($addTemplates, $parentTemplates))) {
        return false;
      }
    }

    // can't create a page if one of the required fields is not legal
    foreach ($template->fields->find("required=1") as $field) {
      if (!in_array($field->name, Utils::module()->legalFields)) {
        return false;
      }
    }

    // superuser can create all pages
    if ($user->isSuperuser()) {
      return true;
    }

    // can't create if access rules are not defined
    if (!self::definesAccess($template)) {
      return false;
    }

    // can't create a page if user does not have create permission on the given template
    if (!$user->hasPermission(self::pageCreatePermission, $template)) {
      return false;
    }

    // can't create a page if one of the required fields is not editable by the user for this template
    foreach ($template->fields->find("required=1") as $field) {
      if (!self::canEditField($field, $template)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Tells if the user can edit the page with the given template.
   *
   * @param Template $template
   * @return boolean
   */
  public static function canEdit(Template $template)
  {
    $user = Utils::user();

    // if superuser then can edit
    if ($user->isSuperuser()) {
      return true;
    }

    // can't edit if no access rules defined
    if (!self::definesAccess($template)) {
      return false;
    }

    // can't edit a page if user does not have edit permission on the given template
    if (!$user->hasPermission(self::pageEditPermission, $template)) {
      return false;
    }

    return true;
  }

  /**
   * Tells if the user can delete a page with the given template.
   *
   * @param Template $template
   * @return boolean
   */
  public static function canDelete(Template $template)
  {
    $user = Utils::user();

    // superuser can delete
    if ($user->isSuperuser()) {
      return true;
    }

    // can't delete a page if access rules are not defined
    if (!self::definesAccess($template)) {
      return false;
    }

    // can't delete if user does not have a delete permission on the given template
    if (!$user->hasPermission(self::pageDeletePermission, $template)) {
      return false;
    }

    return true;
  }

  /**
   * Tells if the user can trash a page with the given template.
   *
   * @param Template $template
   * @return boolean
   */
  public static function canTrash(Template $template)
  {
    $user = Utils::user();

    // superuser can trash
    if ($user->isSuperuser()) {
      return true;
    }

    // can't trash a page if access rules are not defined
    if (!self::definesAccess($template)) {
      return false;
    }

    // can't trash if user does not have a delete or edit-trash-created
    // permission on the given template
    if (
      !$user->hasPermission(self::pageDeletePermission, $template) &&
      !$user->hasPermission(self::pageEditTrashCreatedPermission, $template)
    ) {
      return false;
    }

    return true;
  }

  /**
   * Tells if a page can be added to the given template.
   *
   * @param Template $template
   * @return boolean
   */
  public static function canAdd(Template $template)
  {
    $user = Utils::user();

    // can't add a page if no child is allowed
    if ($template->noChildren == 1) {
      return false;
    }

    // can't add a page if the allowed childTemplates are not legal
    if ($template->noChildren == 0 && count($template->childTemplates)) {
      if (
        !count(
          array_intersect(self::getTemplateIds(), $template->childTemplates)
        )
      ) {
        return false;
      }
    }

    // superuser can add without permissions
    if ($user->isSuperuser()) {
      return true;
    }

    // can't add if access rules are not defined
    if (!self::definesAccess($template)) {
      return false;
    }

    // can't add a page if user does not have add permission on the given template
    if (!$user->hasPermission(self::pageAddPermission, $template)) {
      return false;
    }

    return true;
  }

  /**
   * Tells if the field can be edited by the user within the context of the given template.
   *
   * @param Field $field
   * @param Template $template
   * @return boolean
   */
  public static function canEditField(Field $field, Template $template)
  {
    return self::hasFieldPermission("edit", $field, $template);
  }

  /**
   * Tells if the field can be viewed by the user within the context of the given template.
   *
   * @param Field $field
   * @param Template $template
   * @return boolean
   */
  public static function canViewField(Field $field, Template $template)
  {
    return self::hasFieldPermission("view", $field, $template);
  }

  /**
   * Determines whether the current user has given permission on $field within
   * $template's context.
   * @param  string   $permission The permission type. Either 'view' or 'edit'
   * @param  Field    $field      The field against the check is performed
   * @param  Template $template   The context of the field.
   * @return boolean              Returns true if user has rights and false otherwise
   */
  public static function hasFieldPermission(
    string $permission,
    Field $field,
    Template $template
  ) {
    $user = Utils::user();

    // can view/edit a field if superuser
    if ($user->isSuperuser()) {
      return true;
    }

    // cannot view/edit if access rules are not defined
    $field = $template->fields->getFieldContext($field);
    if (!self::definesAccess($field)) {
      return false;
    }

    $roles = $permission . "Roles";

    foreach ($user->roles as $role) {
      if (in_array($role->id, $field->$roles)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Tells if the template or field has access control defined.
   *
   * @param Template|Field $context
   * @return boolean
   */
  public static function definesAccess($context)
  {
    return (bool) $context->useRoles;
  }

  /**
   * Returns legal templates. The ones user marked in the module settings.
   *
   * @return Templates
   */
  public static function getTemplates()
  {
    $templates = Utils::templates();
    $legalTemplateNames = implode("|", Utils::module()->legalTemplates);
    return $templates->find("name=$legalTemplateNames");
  }

  /**
   * Returns the ids of the legal templates.
   *
   * @return integer[]
   */
  public static function getTemplateIds()
  {
    return array_merge([], self::getTemplates()->explode("id"));
  }

  public static function filterTemplatesByPermission($predicator)
  {
    $templates = self::getTemplates();
    foreach ($templates as $template) {
      if (!$predicator($template)) {
        $templates->remove($template);
      }
    }
    return $templates;
  }

  /**
   * Returns the templates that can be viewed by the current user.
   *
   * @return Templates
   */
  public static function getViewTemplates()
  {
    return self::filterTemplatesByPermission(function (Template $template) {
      return self::canView($template);
    });
  }

  /**
   * Returns the templates that can be created by the current user.
   *
   * @return Templates
   */
  public static function getCreateTemplates()
  {
    return self::filterTemplatesByPermission(function (Template $template) {
      return self::canCreate($template);
    });
  }

  /**
   * Returns the templates that can be edited by the current user.
   *
   * @return Templates
   */
  public static function getEditTemplates()
  {
    return self::filterTemplatesByPermission(function (Template $template) {
      return self::canEdit($template);
    });
  }

  /**
   * Returns the templates that can be deleted by the current user.
   *
   * @return Templates
   */
  public static function getDeleteTemplates()
  {
    return self::filterTemplatesByPermission(function (Template $template) {
      return self::canDelete($template);
    });
  }

  /**
   * Returns the templates that can be moved to trash by the current user.
   *
   * @return Templates
   */
  public static function getTrashTemplates()
  {
    return self::filterTemplatesByPermission(function (Template $template) {
      return self::canTrash($template);
    });
  }

  /**
   * Returns the templates that user can add pages to.
   *
   * @return Templates
   */
  public static function getAddTemplates()
  {
    return self::filterTemplatesByPermission(function (Template $template) {
      return self::canAdd($template);
    });
  }

  public static function turnOnApiAccess()
  {
    foreach (Utils::module()->legalFields as $fieldName) {
      $field = Utils::fields()->get($fieldName);
      $field->flags = $field->flags | Field::flagAccessAPI;
    }
  }
}
